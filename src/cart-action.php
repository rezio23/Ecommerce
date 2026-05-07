<?php

require 'includes/security.php';
startSecureSession();

$action = getPost('action');
$slug = getPost('slug');
$quantity = (int) getPost('quantity', '1');
$size = getPost('size', 'M');

if ($action === 'add' && $slug !== '') {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    require 'includes/db.php';
    $stmt = $pdo->prepare('SELECT * FROM products WHERE slug = :slug');
    $stmt->execute([':slug' => $slug]);
    $product = $stmt->fetch();

    if ($product) {
        $key = $slug . '|' . $size;
        if (isset($_SESSION['cart'][$key])) {
            $_SESSION['cart'][$key]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$key] = [
                'name' => $product['name'],
                'brand' => $product['brand'],
                'price' => (float) $product['price'],
                'image' => $product['image'],
                'quantity' => max(1, $quantity),
                'size' => $size,
            ];
        }
    }

    $redirect = $_SERVER['HTTP_REFERER'] ?? '../index.php';
    header('Location: ' . $redirect);
    exit;
}

if ($action === 'update' && $slug !== '') {
    if (isset($_SESSION['cart'][$slug])) {
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$slug]);
        } else {
            $_SESSION['cart'][$slug]['quantity'] = $quantity;
        }
    }
    header('Location: cart.php');
    exit;
}

if ($action === 'remove' && $slug !== '') {
    unset($_SESSION['cart'][$slug]);
    header('Location: cart.php');
    exit;
}

header('Location: ../index.php');
exit;
