<?php

require 'includes/security.php';
startSecureSession();

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

session_destroy();
header('Location: ../index.php');
exit;
