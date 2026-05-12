<?php

require '../includes/security.php';
require '../includes/db.php';
startSecureSession();

if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare('DELETE FROM remember_tokens WHERE user_id = :user_id');
    $stmt->execute([':user_id' => $_SESSION['user_id']]);
}

$_SESSION = [];

if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', [
        'expires' => time() - 3600,
        'path' => '/',
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
}

if (isset($_COOKIE['remember_me'])) {
    [$selector] = explode(':', $_COOKIE['remember_me'] . ':', 2);
    if (!empty($selector)) {
        clearRememberToken($pdo, $selector);
    }
    $params = getCookieParams();
    setcookie('remember_me', '', [
        'expires' => time() - 3600,
        'path' => $params['path'],
        'domain' => $params['domain'],
        'secure' => $params['secure'],
        'httponly' => $params['httponly'],
        'samesite' => $params['samesite'],
    ]);
}

session_destroy();
header('Location: ../index.php');
exit;
