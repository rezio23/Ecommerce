<?php
require 'includes/security.php';
startSecureSession();

$cartItems = $_SESSION['cart'] ?? [];
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
$bagCount = array_sum(array_column($cartItems, 'quantity'));
$activeButton = 'bag';
$currentPage = 'shop';
$searchTrigger = 'button';
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
            <a href="help-center.php" class="cart-help-link">Help Center</a>
        </div>

        <?php if (empty($cartItems)): ?>
            <div class="cart-empty">
                <i data-lucide="shopping-bag" aria-hidden="true"></i>
                <h2>Your cart is empty</h2>
                <p>Looks like you haven't added anything to your cart yet.</p>
                <a href="shop.php" class="cart-checkout-btn">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="cart-table-wrap">
                <div class="cart-table-header">
                    <span>Product</span>
                    <span>Unit Price</span>
                    <span>Quantity</span>
                    <span>Total</span>
                </div>

                <?php foreach ($cartItems as $slug => $item): ?>
                    <div class="cart-table-row">
                        <div class="cart-product-cell">
                            <img src="<?= htmlspecialchars($item['image']); ?>" alt="<?= htmlspecialchars($item['name']); ?>">
                            <div class="cart-product-meta">
                                <strong><?= htmlspecialchars($item['name']); ?></strong>
                                <span>Size: <?= htmlspecialchars($item['size']); ?></span>
                            </div>
                        </div>
                        <div class="cart-price-cell">$ <?= number_format($item['price'], 2); ?></div>
                        <div class="cart-quantity-cell">
                            <form action="cart-action.php" method="post" class="cart-qty-control">
                                <?= csrfField(); ?>
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="slug" value="<?= htmlspecialchars($slug); ?>">
                                <button type="submit" class="cart-qty-btn" name="quantity" value="<?= max(0, $item['quantity'] - 1); ?>" aria-label="Decrease quantity">
                                    <i data-lucide="minus" aria-hidden="true"></i>
                                </button>
                                <span class="cart-qty-value"><?= (int) $item['quantity']; ?></span>
                                <button type="submit" class="cart-qty-btn" name="quantity" value="<?= $item['quantity'] + 1; ?>" aria-label="Increase quantity">
                                    <i data-lucide="plus" aria-hidden="true"></i>
                                </button>
                            </form>
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
        <?php endif; ?>
    </main>



<?php include 'includes/footer.php'; ?>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/app.js?v=23"></script>
</body>
</html>
