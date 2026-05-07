<?php

require 'includes/security.php';
startSecureSession();
require 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = (int) $_SESSION['user_id'];
$errors = [];

$cartItems = $_SESSION['cart'] ?? [];
if (empty($cartItems)) {
    header('Location: cart.php');
    exit;
}

$subtotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cartItems));
$selectedShippingMode = ($_SERVER['REQUEST_METHOD'] === 'POST') ? (getPost('shipping_mode') === 'fast' ? 'fast' : 'standard') : 'standard';
$shipping = $selectedShippingMode === 'fast' ? 5.99 : 2.99;
$taxRate = 0.018;
$taxes = round($subtotal * $taxRate, 2);
$total = $subtotal + $shipping + $taxes;

$stmt = $pdo->prepare('SELECT full_name, phone, address, email FROM users WHERE id = :id');
$stmt->execute([':id' => $userId]);
$dbUser = $stmt->fetch();

$user = [
    'name' => $dbUser['full_name'] ?? 'User',
    'phone' => $dbUser['phone'] ?? '',
    'address1' => $dbUser['address'] ?? '',
    'address2' => '',
    'postal' => '',
    'email' => $dbUser['email'] ?? '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();

    $fullName = trim((string) getPost('full_name'));
    $phone = trim((string) getPost('phone'));
    $address1 = trim((string) getPost('address_1'));
    $address2 = trim((string) getPost('address_2'));
    $postal = trim((string) getPost('postal_code'));
    $email = trim((string) getPost('email'));
    $description = trim((string) getPost('description'));
    $shippingMode = getPost('shipping_mode') === 'fast' ? 'fast' : 'standard';

    if ($fullName === '') $errors[] = 'Full name is required.';
    if ($phone === '') $errors[] = 'Phone is required.';
    if ($address1 === '') $errors[] = 'Address Line 1 is required.';
    if ($postal === '') $errors[] = 'Postal code is required.';
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';

    if (empty($errors)) {
        $_SESSION['shipping'] = [
            'full_name' => $fullName,
            'phone' => $phone,
            'address_1' => $address1,
            'address_2' => $address2,
            'postal_code' => $postal,
            'email' => $email,
            'description' => $description,
            'shipping_mode' => $shippingMode,
            'shipping_cost' => $shippingMode === 'fast' ? 5.99 : 2.99,
        ];
        header('Location: payment.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping | The DS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Doto:wght@400;600;700;800&family=Krona+One&family=Modak&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css?v=94">
</head>
<body class="shipping-page">

<?php
$headerId = 'shipping-top';
$searchId = 'header-shipping-search';
$bagCount = array_sum(array_column($cartItems, 'quantity'));
$activeButton = '';
$currentPage = 'shop';
$searchTrigger = 'button';
$rootPath = '../';
$srcPath = '';
?>

<?php include 'includes/navbar.php'; ?>
<main class="shipping-main">
    <div class="shipping-header-row">
        <nav class="shipping-breadcrumb" aria-label="Checkout steps">
            <a href="cart.php">Cart</a>
            <span>/</span>
            <span class="is-active" aria-current="step">Shipping</span>
            <span>/</span>
            <span>Payment</span>
        </nav>
        <a href="#" class="shipping-help-link">Help Center</a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="form-errors" style="max-width: 800px; margin: 1rem auto; color: #c00; background: #ffeaea; padding: 1rem; border-radius: 8px;">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="shipping-layout">
        <section class="shipping-form-card" aria-label="Shipping details">
            <h2 class="shipping-card-title">Shipping Detail</h2>
            <form action="shipping.php" method="post" class="shipping-form" id="shipping-form">
                <?= csrfField(); ?>
                <div class="shipping-form-row">
                    <div class="shipping-form-group">
                        <label for="ship-full-name">Full name</label>
                        <input type="text" id="ship-full-name" name="full_name" value="<?= htmlspecialchars($user['name']); ?>" required>
                    </div>
                    <div class="shipping-form-group">
                        <label for="ship-phone">Phone</label>
                        <input type="tel" id="ship-phone" name="phone" value="<?= htmlspecialchars($user['phone']); ?>" required>
                    </div>
                </div>

                <div class="shipping-form-row">
                    <div class="shipping-form-group">
                        <label for="ship-address-1">Address Line 1</label>
                        <input type="text" id="ship-address-1" name="address_1" value="<?= htmlspecialchars($user['address1']); ?>" required>
                    </div>
                    <div class="shipping-form-group">
                        <label for="ship-address-2">Address Line 2</label>
                        <input type="text" id="ship-address-2" name="address_2" value="<?= htmlspecialchars($user['address2']); ?>">
                    </div>
                </div>

                <div class="shipping-form-row">
                    <div class="shipping-form-group shipping-form-group--wide">
                        <label for="ship-description">Description</label>
                        <input type="text" id="ship-description" name="description" placeholder="Enter a description...">
                    </div>
                </div>

                <div class="shipping-form-row">
                    <div class="shipping-form-group">
                        <label for="ship-postal">Postal Code</label>
                        <input type="text" id="ship-postal" name="postal_code" value="<?= htmlspecialchars($user['postal']); ?>" required>
                    </div>
                    <div class="shipping-form-group">
                        <label for="ship-email">Email</label>
                        <input type="email" id="ship-email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
                    </div>
                </div>

                <fieldset class="shipping-mode">
                    <legend>Shipping Mode</legend>
                    <div class="shipping-mode-options">
                        <label class="shipping-mode-option">
                            <input type="radio" name="shipping_mode" value="standard" <?= $selectedShippingMode === 'standard' ? 'checked' : ''; ?>>
                            <span class="shipping-mode-check"></span>
                            <span class="shipping-mode-info">
                                <strong>Standard Delivery</strong>
                                <span>9 - 14 Days</span>
                            </span>
                            <span class="shipping-mode-price">2.99$</span>
                        </label>
                        <label class="shipping-mode-option">
                            <input type="radio" name="shipping_mode" value="fast" <?= $selectedShippingMode === 'fast' ? 'checked' : ''; ?>>
                            <span class="shipping-mode-check"></span>
                            <span class="shipping-mode-info">
                                <strong>Fast Delivery</strong>
                                <span>5 - 7 Days</span>
                            </span>
                            <span class="shipping-mode-price">5.99$</span>
                        </label>
                    </div>
                </fieldset>
            </form>
        </section>

        <aside class="shipping-summary-card" aria-label="Order summary">
            <h2 class="shipping-card-title">Cart</h2>
            <div class="shipping-cart-items">
                <?php foreach (array_values($cartItems) as $index => $item): ?>
                    <div class="shipping-cart-item">
                        <div class="shipping-cart-thumb">
                            <img src="<?= htmlspecialchars($item['image']); ?>" alt="<?= htmlspecialchars($item['name']); ?>">
                            <span class="shipping-cart-badge"><?= $index + 1; ?></span>
                        </div>
                        <div class="shipping-cart-info">
                            <strong><?= htmlspecialchars($item['name']); ?></strong>
                            <span>Quantity: <?= (int) $item['quantity']; ?></span>
                            <span class="shipping-cart-size">Size: <?= htmlspecialchars($item['size'] ?? 'One Size'); ?></span>
                        </div>
                        <span class="shipping-cart-price">$ <?= number_format($item['price'], 2); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="shipping-promo">
                <input type="text" placeholder="Apply Promo Code" aria-label="Promo code">
                <button type="button">Apply</button>
            </div>

            <hr class="shipping-divider">

            <dl class="shipping-costs">
                <div>
                    <dt>Subtotal</dt>
                    <dd>$ <?= number_format($subtotal, 2); ?></dd>
                </div>
                <div>
                    <dt>Shipping</dt>
                    <dd>$ <?= number_format($shipping, 2); ?></dd>
                </div>
                <div>
                    <dt>Taxes (1.8%)</dt>
                    <dd>$ <?= number_format($taxes, 2); ?></dd>
                </div>
            </dl>

            <hr class="shipping-divider">

            <div class="shipping-total">
                <span>Total</span>
                <strong>$ <?= number_format($total, 2); ?></strong>
            </div>

            <button type="submit" form="shipping-form" class="shipping-checkout-btn">Continue to Payment</button>
        </aside>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="../assets/js/app.js?v=23"></script>
</body>
</html>
