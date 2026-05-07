<?php

require 'includes/security.php';
startSecureSession();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

requireCsrf();
require 'includes/db.php';

$productId = (int) getPost('product_id');
$userId = (int) $_SESSION['user_id'];

if ($productId > 0) {
    $check = $pdo->prepare('SELECT id FROM favorites WHERE user_id = :user_id AND product_id = :product_id');
    $check->execute([':user_id' => $userId, ':product_id' => $productId]);

    if ($check->fetch()) {
        $delete = $pdo->prepare('DELETE FROM favorites WHERE user_id = :user_id AND product_id = :product_id');
        $delete->execute([':user_id' => $userId, ':product_id' => $productId]);
    } else {
        $insert = $pdo->prepare('INSERT INTO favorites (user_id, product_id) VALUES (:user_id, :product_id)');
        $insert->execute([':user_id' => $userId, ':product_id' => $productId]);
    }
}

$redirect = $_SERVER['HTTP_REFERER'] ?? '../index.php';
header('Location: ' . $redirect);
exit;
