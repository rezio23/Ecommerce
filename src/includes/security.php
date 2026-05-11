<?php

require_once __DIR__ . '/env.php';

// Load environment variables from project root
$envPath = realpath(__DIR__ . '/../../.env');
if ($envPath && file_exists($envPath)) {
    loadEnv($envPath);
}

// =====================
// Security Headers
// =====================
function setSecurityHeaders(): void
{
    header('X-Frame-Options: DENY');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://unpkg.com https://code.jquery.com https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src https://fonts.gstatic.com; img-src * data:; connect-src 'self'; frame-ancestors 'none'; base-uri 'self'; form-action 'self';");
    header('X-XSS-Protection: 1; mode=block');
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
}

function getCookieParams(): array
{
    $secure = filter_var(env('SESSION_SECURE', 'false'), FILTER_VALIDATE_BOOL);
    $httponly = filter_var(env('SESSION_HTTPONLY', 'true'), FILTER_VALIDATE_BOOL);
    $samesite = env('SESSION_SAMESITE', 'Strict');
    return [
        'path' => '/',
        'domain' => '',
        'secure' => $secure,
        'httponly' => $httponly,
        'samesite' => $samesite,
    ];
}

// =====================
// Session Security
// =====================
function startSecureSession(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        $sessionName = env('SESSION_NAME', 'the_ds_session');
        session_name($sessionName);

        $secure = filter_var(env('SESSION_SECURE', 'false'), FILTER_VALIDATE_BOOL);
        $httponly = filter_var(env('SESSION_HTTPONLY', 'true'), FILTER_VALIDATE_BOOL);
        $samesite = env('SESSION_SAMESITE', 'Strict');

        $cookieParams = [
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => $secure,
            'httponly' => $httponly,
            'samesite' => $samesite,
        ];

        if (PHP_VERSION_ID >= 70300) {
            session_set_cookie_params($cookieParams);
        } else {
            session_set_cookie_params(
                $cookieParams['lifetime'],
                $cookieParams['path'],
                $cookieParams['domain'],
                $cookieParams['secure'],
                $cookieParams['httponly']
            );
        }

        session_start();

        if (empty($_SESSION['initiated'])) {
            session_regenerate_id(true);
            $_SESSION['initiated'] = true;
            $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'] ?? '';
            $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
        }

        // Session validation
        $currentIp = $_SERVER['REMOTE_ADDR'] ?? '';
        $currentUa = $_SERVER['HTTP_USER_AGENT'] ?? '';
        if (($_SESSION['ip'] ?? '') !== $currentIp || ($_SESSION['user_agent'] ?? '') !== $currentUa) {
            session_destroy();
            session_start();
            session_regenerate_id(true);
            $_SESSION['initiated'] = true;
            $_SESSION['ip'] = $currentIp;
            $_SESSION['user_agent'] = $currentUa;
        }
    }
}

function createRememberToken(PDO $pdo, int $userId): void
{
    $selector = bin2hex(random_bytes(16));
    $validator = bin2hex(random_bytes(32));
    $tokenHash = hash('sha256', $validator);
    $expires = date('Y-m-d H:i:s', time() + 30 * 24 * 60 * 60);

    $stmt = $pdo->prepare('INSERT INTO remember_tokens (user_id, selector, token_hash, expires_at) VALUES (:user_id, :selector, :token_hash, :expires_at)');
    $stmt->execute([
        ':user_id' => $userId,
        ':selector' => $selector,
        ':token_hash' => $tokenHash,
        ':expires_at' => $expires,
    ]);

    $cookieValue = $selector . ':' . $validator;
    $params = getCookieParams();
    setcookie('remember_me', $cookieValue, [
        'expires' => time() + 30 * 24 * 60 * 60,
        'path' => $params['path'],
        'domain' => $params['domain'],
        'secure' => $params['secure'],
        'httponly' => $params['httponly'],
        'samesite' => $params['samesite'],
    ]);
}

function clearRememberToken(PDO $pdo, string $selector): void
{
    $stmt = $pdo->prepare('DELETE FROM remember_tokens WHERE selector = :selector');
    $stmt->execute([':selector' => $selector]);
}

function tryAutoLogin(PDO $pdo): void
{
    if (isset($_SESSION['user_id'])) {
        return;
    }

    $cookie = $_COOKIE['remember_me'] ?? '';
    if (empty($cookie) || strpos($cookie, ':') === false) {
        return;
    }

    [$selector, $validator] = explode(':', $cookie, 2);
    if (empty($selector) || empty($validator)) {
        return;
    }

    $stmt = $pdo->prepare('SELECT t.*, u.full_name, u.email FROM remember_tokens t JOIN users u ON t.user_id = u.id WHERE t.selector = :selector AND t.expires_at > NOW()');
    $stmt->execute([':selector' => $selector]);
    $row = $stmt->fetch();

    if (!$row) {
        $params = getCookieParams();
        setcookie('remember_me', '', [
            'expires' => time() - 3600,
            'path' => $params['path'],
            'domain' => $params['domain'],
            'secure' => $params['secure'],
            'httponly' => $params['httponly'],
            'samesite' => $params['samesite'],
        ]);
        return;
    }

    if (!hash_equals($row['token_hash'], hash('sha256', $validator))) {
        return;
    }

    $_SESSION['user_id'] = $row['user_id'];
    $_SESSION['user_name'] = $row['full_name'];
    $_SESSION['user_email'] = $row['email'];
}

// =====================
// CSRF Protection
// =====================
function generateCsrfToken(): string
{
    startSecureSession();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function getCsrfToken(): string
{
    return generateCsrfToken();
}

function validateCsrfToken(?string $token): bool
{
    startSecureSession();
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string) $token);
}

function csrfField(): string
{
    $token = htmlspecialchars(getCsrfToken(), ENT_QUOTES, 'UTF-8');
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

function requireCsrf(): void
{
    $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!validateCsrfToken($token)) {
        http_response_code(403);
        exit('Invalid or missing CSRF token.');
    }
}

// =====================
// Input Sanitization
// =====================
function sanitizeInput(?string $value): string
{
    if ($value === null) {
        return '';
    }
    $value = trim($value);
    $value = stripslashes($value);
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function getGet(string $key, string $default = ''): string
{
    $value = $_GET[$key] ?? $default;
    return sanitizeInput(is_array($value) ? '' : (string) $value);
}

function getPost(string $key, string $default = ''): string
{
    $value = $_POST[$key] ?? $default;
    return sanitizeInput(is_array($value) ? '' : (string) $value);
}

function getPostArray(string $key): array
{
    $value = $_POST[$key] ?? [];
    return is_array($value) ? $value : [];
}

function validateEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// =====================
// File Upload Security
// =====================
function validateFileUpload(array $file, array $allowedMimeTypes, int $maxBytes): array
{
    $result = ['ok' => false, 'error' => '', 'path' => ''];

    if (empty($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
        $result['error'] = 'Upload failed or no file provided.';
        return $result;
    }

    if ($file['size'] > $maxBytes) {
        $result['error'] = 'File is too large.';
        return $result;
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);

    if (!in_array($mime, $allowedMimeTypes, true)) {
        $result['error'] = 'Invalid file type.';
        return $result;
    }

    // Verify image dimensions if it's an image
    if (str_starts_with($mime, 'image/')) {
        $dims = getimagesize($file['tmp_name']);
        if ($dims === false) {
            $result['error'] = 'Invalid image file.';
            return $result;
        }
    }

    $ext = pathinfo((string) $file['name'], PATHINFO_EXTENSION);
    $safeName = bin2hex(random_bytes(16)) . '.' . strtolower($ext);
    $uploadDir = realpath(__DIR__ . '/../../uploads') ?: __DIR__ . '/../../uploads';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0750, true);
    }

    $target = $uploadDir . DIRECTORY_SEPARATOR . $safeName;

    if (!move_uploaded_file($file['tmp_name'], $target)) {
        $result['error'] = 'Failed to save file.';
        return $result;
    }

    chmod($target, 0640);
    $result['ok'] = true;
    $result['path'] = $target;
    $result['name'] = $safeName;
    return $result;
}

// =====================
// Error Handling
// =====================
function safeErrorHandler(): void
{
    $debug = filter_var(env('APP_DEBUG', 'false'), FILTER_VALIDATE_BOOL);
    if (!$debug) {
        ini_set('display_errors', '0');
        ini_set('log_errors', '1');
        error_reporting(E_ALL);
    }
}

// =====================
// Initialize
// =====================
setSecurityHeaders();
safeErrorHandler();
