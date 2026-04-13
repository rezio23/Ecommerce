<?php
require __DIR__ . '/data.php';
require __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_item'])) {
        $productId = (int) ($_POST['product_id'] ?? 0);
        $quantity = (int) ($_POST['quantity'] ?? 1);

        if (getProductById($products, $productId) !== null) {
            updateCartItem($productId, $quantity);
        }

        header('Location: cart.php?updated=1');
        exit;
    }

    if (isset($_POST['checkout'])) {
        header('Location: checkout.php');
        exit;
    }
}

if (isset($_GET['remove'])) {
    $productId = (int) $_GET['remove'];
    removeFromCart($productId);
    header('Location: cart.php?removed=1');
    exit;
}

$cartItems = getCartItems($products);
$subtotal = getCartSubtotal($products);
$total = $subtotal;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart | <?= htmlspecialchars($site['brand']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php require __DIR__ . '/includes/header.php'; ?>

    <main class="cart-page section-space">
        <div class="container">
            <?php if (isset($_GET['updated'])): ?>
                <div class="notice success single-notice">Cart quantity updated successfully.</div>
            <?php endif; ?>

            <?php if (isset($_GET['removed'])): ?>
                <div class="notice success single-notice">Product removed from cart.</div>
            <?php endif; ?>

            <?php if (isset($_GET['checkout'])): ?>
                <div class="notice success single-notice">Checkout button clicked. You can connect this to your real payment flow later.</div>
            <?php endif; ?>

            <section class="cart-section">
                <?php if (empty($cartItems)): ?>
                    <div class="empty-cart-box">
                        <h1>Your Cart</h1>
                        <p>Your shopping bag is empty right now.</p>
                        <a href="index.php#products" class="primary-btn">Continue Shopping</a>
                    </div>
                <?php else: ?>
                    <div class="cart-table-wrap">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cartItems as $item): ?>
                                    <?php $product = $item['product']; ?>
                                    <tr>
                                        <td>
                                            <div class="cart-product-cell">
                                                <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                                <div class="cart-product-info">
                                                    <a href="singleproduct.php?id=<?= (int) $product['id'] ?>" class="cart-product-name">
                                                        <?= htmlspecialchars($product['name']) ?>
                                                    </a>
                                                    <p><?= money((float) $product['price']) ?></p>
                                                    <a href="cart.php?remove=<?= (int) $product['id'] ?>" class="remove-link">Remove</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <form action="cart.php" method="post" class="quantity-form">
                                                <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                                                <input type="number" min="1" max="10" name="quantity" value="<?= (int) $item['quantity'] ?>" class="cart-qty-input">
                                                <button type="submit" name="update_item" class="edit-link">Edit</button>
                                            </form>
                                        </td>
                                        <td class="cart-subtotal-cell"><?= money((float) $item['subtotal']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="cart-total-box">
                        <table>
                            <tr>
                                <td>Subtotal</td>
                                <td><?= money((float) $subtotal) ?></td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td><?= money((float) $total) ?></td>
                            </tr>
                        </table>

                        <form action="cart.php" method="post" class="checkout-form">
                            <button type="submit" name="checkout" class="primary-btn checkout-btn">Checkout</button>
                        </form>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </main>

    <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
