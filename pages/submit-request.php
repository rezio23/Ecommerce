<?php

require '../includes/security.php';
require '../includes/db.php';
startSecureSession();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed.']);
    exit;
}

requireCsrf();

$phone = getPost('phone');
$email = getPost('email');
$subject = getPost('subject');
$message = getPost('message');

if (empty($phone) || mb_strlen($phone) > 50) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Please provide your phone number.']);
    exit;
}

if (empty($email) || !validateEmail($email)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Please provide a valid email address.']);
    exit;
}

if (empty($subject) || mb_strlen($subject) > 120) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Subject is required and must be 120 characters or fewer.']);
    exit;
}

if (empty($message) || mb_strlen($message) > 2000) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Message is required and must be 2000 characters or fewer.']);
    exit;
}

$userId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;

$attachmentPath = '';
if (!empty($_FILES['attachment']['tmp_name'])) {
    $result = validateFileUpload(
        $_FILES['attachment'],
        ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf'],
        5 * 1024 * 1024
    );
    if (!$result['ok']) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => $result['error']]);
        exit;
    }

    $uploadDir = realpath(__DIR__ . '/../uploads/support') ?: __DIR__ . '/../uploads/support';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0750, true);
    }

    $origName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $_FILES['attachment']['name']);
    $destPath = $uploadDir . '/' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '_' . $origName;

    if (!rename($result['path'], $destPath)) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Failed to save attachment.']);
        exit;
    }

    $attachmentPath = str_replace(realpath(__DIR__ . '/..') ?: __DIR__ . '/..', '', realpath($destPath));
    $attachmentPath = ltrim(str_replace('\\', '/', $attachmentPath), '/');
}

$stmt = $pdo->prepare(
    'INSERT INTO user_requests (user_id, phone, email, subject, message, attachment, status, created_at) ' .
    'VALUES (:user_id, :phone, :email, :subject, :message, :attachment, :status, NOW())'
);
$stmt->execute([
    ':user_id' => $userId,
    ':phone' => $phone,
    ':email' => $email,
    ':subject' => $subject,
    ':message' => $message,
    ':attachment' => $attachmentPath,
    ':status' => 'pending',
]);

echo json_encode(['success' => true]);
exit;
