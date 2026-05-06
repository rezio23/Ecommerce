<?php

$cartItems = [
    [
        'name' => 'Paradigme Eau de Parfum',
        'brand' => 'Prada',
        'size' => '150ml',
        'price' => 19.99,
        'quantity' => 1,
        'image' => 'https://cosmeticsbusiness.com/article-image-alias/spider-man-s-tom-holland-swings-into-prada.jpg',
    ],
    [
        'name' => 'Paradigme Eau de Parfum',
        'brand' => 'Prada',
        'size' => '150ml',
        'price' => 19.99,
        'quantity' => 1,
        'image' => 'https://perfumeuae.com/wp-content/uploads/2025/08/para-1.jpg',
    ],
];

$subtotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cartItems));
$shipping = 2.99;
$taxRate = 0.018;
$taxes = round($subtotal * $taxRate, 2);
$total = $subtotal + $shipping + $taxes;

$user = [
    'name' => 'Vichhean Sombath',
    'phone' => 'Unknown',
    'address1' => 'Phnom Penh',
    'address2' => 'Sen Sok',
    'postal' => '12000',
    'email' => 'sombath@gmail.com',
];
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
$bagCount = count($cartItems);
$activeButton = '';
$currentPage = 'shop';
$searchTrigger = 'link';
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

        <div class="shipping-layout">
            <section class="shipping-form-card" aria-label="Shipping details">
                <h2 class="shipping-card-title">Shipping Detail</h2>
                <form action="payment.php" method="post" class="shipping-form">
                    <div class="shipping-form-row">
                        <div class="shipping-form-group">
                            <label for="ship-full-name">Full name</label>
                            <input type="text" id="ship-full-name" name="full_name" value="<?= htmlspecialchars($user['name']); ?>">
                        </div>
                        <div class="shipping-form-group">
                            <label for="ship-phone">Phone</label>
                            <input type="tel" id="ship-phone" name="phone" value="<?= $user['phone'] !== 'Unknown' ? htmlspecialchars($user['phone']) : ''; ?>">
                        </div>
                    </div>

                    <div class="shipping-form-row">
                        <div class="shipping-form-group">
                            <label for="ship-address-1">Address Line 1</label>
                            <input type="text" id="ship-address-1" name="address_1" value="<?= htmlspecialchars($user['address1']); ?>">
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
                            <input type="text" id="ship-postal" name="postal_code" value="<?= htmlspecialchars($user['postal']); ?>">
                        </div>
                        <div class="shipping-form-group">
                            <label for="ship-email">Email</label>
                            <input type="email" id="ship-email" name="email" value="<?= htmlspecialchars($user['email']); ?>">
                        </div>
                    </div>

                    <fieldset class="shipping-mode">
                        <legend>Shipping Mode</legend>
                        <div class="shipping-mode-options">
                            <label class="shipping-mode-option">
                                <input type="radio" name="shipping_mode" value="standard" checked>
                                <span class="shipping-mode-check"></span>
                                <span class="shipping-mode-info">
                                    <strong>Standard Delivery</strong>
                                    <span>9 - 14 Days</span>
                                </span>
                                <span class="shipping-mode-price">2.99$</span>
                            </label>
                            <label class="shipping-mode-option">
                                <input type="radio" name="shipping_mode" value="fast">
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
                    <?php foreach ($cartItems as $index => $item): ?>
                        <div class="shipping-cart-item">
                            <div class="shipping-cart-thumb">
                                <img src="<?= htmlspecialchars($item['image']); ?>" alt="<?= htmlspecialchars($item['name']); ?>">
                                <span class="shipping-cart-badge"><?= $index + 1; ?></span>
                            </div>
                            <div class="shipping-cart-info">
                                <strong><?= htmlspecialchars($item['name']); ?></strong>
                                <span>Quantity: <?= (int) $item['quantity']; ?></span>
                                <span class="shipping-cart-size">Size: <?= htmlspecialchars($item['size']); ?></span>
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

                <a href="payment.php" class="shipping-checkout-btn">Continue to Payment</a>
            </aside>
        </div>
    </main>

    

<?php include 'includes/footer.php'; ?>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/app.js?v=23"></script>
</body>
</html>
