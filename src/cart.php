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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart | The DS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Doto:wght@400;600;700;800&family=Krona+One&family=Modak&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css?v=93">
</head>
<body class="cart-page">
    

<?php
$headerId = 'cart-top';
$searchId = 'header-cart-search';
$bagCount = count($cartItems);
$activeButton = 'bag';
$currentPage = 'shop';
$searchTrigger = 'link';
$rootPath = '../';
$srcPath = '';
?>

<?php include 'includes/navbar.php'; ?>
<main class="cart-main">
        <div class="cart-header-row">
            <nav class="cart-breadcrumb" aria-label="Breadcrumb">
                <a href="../index.php#home">Home</a>
                <span>/</span>
                <a href="shop.php">Shop</a>
                <span>/</span>
                <span aria-current="page">Cart</span>
            </nav>
            <a href="#" class="cart-help-link">Help Center</a>
        </div>

        <div class="cart-table-wrap">
            <div class="cart-table-header">
                <span>Product</span>
                <span>Unit Price</span>
                <span>Quantity</span>
                <span>Total</span>
            </div>

            <?php foreach ($cartItems as $index => $item): ?>
                <div class="cart-table-row <?= $index % 2 === 0 ? 'cart-table-row--even' : 'cart-table-row--odd'; ?>">
                    <div class="cart-product-cell">
                        <img src="<?= htmlspecialchars($item['image']); ?>" alt="<?= htmlspecialchars($item['name']); ?>">
                        <div class="cart-product-meta">
                            <strong><?= htmlspecialchars($item['name']); ?></strong>
                            <span>Size: <?= htmlspecialchars($item['size']); ?></span>
                        </div>
                    </div>
                    <div class="cart-price-cell">$ <?= number_format($item['price'], 2); ?></div>
                    <div class="cart-quantity-cell">
                        <div class="cart-qty-control">
                            <button type="button" class="cart-qty-btn" data-cart-qty="-1" aria-label="Decrease quantity">
                                <i data-lucide="minus" aria-hidden="true"></i>
                            </button>
                            <span class="cart-qty-value"><?= (int) $item['quantity']; ?></span>
                            <button type="button" class="cart-qty-btn" data-cart-qty="1" aria-label="Increase quantity">
                                <i data-lucide="plus" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <div class="cart-total-cell">$ <?= number_format($item['price'] * $item['quantity'], 2); ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="cart-summary">
            <hr class="cart-summary-line">
            <div class="cart-subtotal">
                <span>Sub Total:</span>
                <strong>$ <?= number_format($subtotal, 2); ?></strong>
            </div>
            <a href="shipping.php" class="cart-checkout-btn">Go to Checkout</a>
        </div>
    </main>

    

<?php include 'includes/footer.php'; ?>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/app.js?v=23"></script>
    <script>
        document.querySelectorAll('[data-cart-qty]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const row = btn.closest('.cart-table-row');
                const valueEl = row?.querySelector('.cart-qty-value');
                if (!valueEl) return;
                let qty = parseInt(valueEl.textContent, 10) || 0;
                const delta = parseInt(btn.dataset.cartQty, 10) || 0;
                qty = Math.max(1, qty + delta);
                valueEl.textContent = String(qty);
            });
        });
    </script>
</body>
</html>
