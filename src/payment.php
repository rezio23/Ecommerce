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

$shippingInfo = $_SESSION['shipping'] ?? null;
if (!$shippingInfo) {
    header('Location: shipping.php');
    exit;
}

$subtotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cartItems));
$shippingCost = (float) ($shippingInfo['shipping_cost'] ?? 2.99);
$taxRate = 0.018;
$taxes = round($subtotal * $taxRate, 2);
$total = $subtotal + $shippingCost + $taxes;

$qrData = 'KHQR|theDS|' . number_format($total, 2) . '|USD';
$qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' . urlencode($qrData);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();

    $cardNumber = trim((string) getPost('card_number'));
    $cardExpiry = trim((string) getPost('card_expiry'));
    $cardCvc = trim((string) getPost('card_cvc'));
    $cardName = trim((string) getPost('card_name'));

    $hasCardFields = $cardNumber !== '' || $cardExpiry !== '' || $cardCvc !== '' || $cardName !== '';
    $paymentMethod = $hasCardFields ? 'debit_card' : 'khqr';

    if ($paymentMethod === 'debit_card') {
        if ($cardNumber === '') $errors[] = 'Card number is required.';
        if ($cardExpiry === '') $errors[] = 'Expiry date is required.';
        if ($cardCvc === '') $errors[] = 'CVC is required.';
        if ($cardName === '') $errors[] = 'Cardholder name is required.';
    }

    if (empty($errors)) {
        $fullAddress = $shippingInfo['address_1'];
        if (!empty($shippingInfo['address_2'])) {
            $fullAddress .= ', ' . $shippingInfo['address_2'];
        }

        $orderStmt = $pdo->prepare('INSERT INTO orders (user_id, total, status, shipping_name, shipping_phone, shipping_address, shipping_postal, shipping_email, shipping_mode, created_at) VALUES (:user_id, :total, :status, :shipping_name, :shipping_phone, :shipping_address, :shipping_postal, :shipping_email, :shipping_mode, NOW())');
        $orderStmt->execute([
            ':user_id' => $userId,
            ':total' => $total,
            ':status' => 'pending',
            ':shipping_name' => $shippingInfo['full_name'],
            ':shipping_phone' => $shippingInfo['phone'],
            ':shipping_address' => $fullAddress,
            ':shipping_postal' => $shippingInfo['postal_code'],
            ':shipping_email' => $shippingInfo['email'],
            ':shipping_mode' => $shippingInfo['shipping_mode'] ?? 'standard',
        ]);
        $orderId = (int) $pdo->lastInsertId();

        $itemStmt = $pdo->prepare('INSERT INTO order_items (order_id, product_name, product_brand, product_price, quantity, size, product_image, created_at) VALUES (:order_id, :product_name, :product_brand, :product_price, :quantity, :size, :product_image, NOW())');
        foreach ($cartItems as $item) {
            $itemStmt->execute([
                ':order_id' => $orderId,
                ':product_name' => $item['name'],
                ':product_brand' => $item['brand'],
                ':product_price' => $item['price'],
                ':quantity' => $item['quantity'],
                ':size' => $item['size'] ?? 'One Size',
                ':product_image' => $item['image'] ?? '',
            ]);
        }

        unset($_SESSION['cart'], $_SESSION['shipping']);
        header('Location: profile.php?ordered=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment | The DS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Doto:wght@400;600;700;800&family=Krona+One&family=Modak&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css?v=96">
</head>
<body class="payment-page">

<?php
$headerId = 'payment-top';
$searchId = 'header-payment-search';
$bagCount = array_sum(array_column($cartItems, 'quantity'));
$activeButton = '';
$currentPage = 'shop';
$searchTrigger = 'button';
$rootPath = '../';
$srcPath = '';
?>

<?php include 'includes/navbar.php'; ?>
<main class="payment-main">
    <div class="payment-header-row">
        <nav class="payment-breadcrumb" aria-label="Checkout steps">
            <a href="cart.php">Cart</a>
            <span>/</span>
            <a href="shipping.php">Shipping</a>
            <span>/</span>
            <span class="is-active" aria-current="step">Payment</span>
        </nav>
        <a href="#" class="payment-help-link">Help Center</a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="form-errors" style="max-width: 800px; margin: 1rem auto; color: #c00; background: #ffeaea; padding: 1rem; border-radius: 8px;">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="payment.php" method="post" class="payment-layout-form" id="payment-form" autocomplete="off">
        <?= csrfField(); ?>
        <div class="payment-layout">
            <section class="payment-form-card" aria-label="Payment details">
                <h2 class="payment-card-title">Payment Method</h2>

                <div class="payment-step-tabs" role="tablist" aria-label="Payment methods">
                    <button type="button" class="payment-step-tab is-active" role="tab" aria-selected="true" aria-controls="panel-khqr" id="tab-khqr">
                        KHQR
                    </button>
                    <button type="button" class="payment-step-tab" role="tab" aria-selected="false" aria-controls="panel-card" id="tab-card" tabindex="-1">
                        Debit Card
                    </button>
                </div>

                <div class="payment-tab-panels">
                    <div id="panel-khqr" class="payment-tab-panel is-active" role="tabpanel" aria-labelledby="tab-khqr">
                        <div class="payment-khqr">
                            <div class="payment-khqr-qr">
                                <img src="<?= htmlspecialchars($qrUrl); ?>" alt="KHQR payment code for <?= number_format($total, 2); ?> USD">
                            </div>
                            <p class="payment-khqr-instruction">Scan this QR code with your Bakong or banking app to complete payment.</p>
                            <div class="payment-khqr-meta">
                                <span>Merchant: <strong>the DS</strong></span>
                                <span>Amount: <strong>$ <?= number_format($total, 2); ?></strong></span>
                            </div>
                        </div>
                    </div>

                    <div id="panel-card" class="payment-tab-panel" role="tabpanel" aria-labelledby="tab-card" hidden>
                        <div class="payment-card-form">
                            <div class="payment-form-group payment-form-group--wide">
                                <label for="card-number">Card Number</label>
                                <input type="text" id="card-number" name="card_number" placeholder="0000 0000 0000 0000" maxlength="19" inputmode="numeric">
                            </div>

                            <div class="payment-form-row">
                                <div class="payment-form-group">
                                    <label for="card-expiry">Expiry Date</label>
                                    <input type="text" id="card-expiry" name="card_expiry" placeholder="MM / YY" maxlength="7" inputmode="numeric">
                                </div>
                                <div class="payment-form-group">
                                    <label for="card-cvc">CVC</label>
                                    <input type="text" id="card-cvc" name="card_cvc" placeholder="123" maxlength="4" inputmode="numeric">
                                </div>
                            </div>

                            <div class="payment-form-group payment-form-group--wide">
                                <label for="card-name">Cardholder Name</label>
                                <input type="text" id="card-name" name="card_name" placeholder="Name on card">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <aside class="payment-summary-card" aria-label="Order summary">
                <h2 class="payment-card-title">Cart</h2>
                <div class="payment-cart-items">
                    <?php foreach (array_values($cartItems) as $index => $item): ?>
                        <div class="payment-cart-item">
                            <div class="payment-cart-thumb">
                                <img src="<?= htmlspecialchars($item['image']); ?>" alt="<?= htmlspecialchars($item['name']); ?>">
                                <span class="payment-cart-badge"><?= $index + 1; ?></span>
                            </div>
                            <div class="payment-cart-info">
                                <strong><?= htmlspecialchars($item['name']); ?></strong>
                                <span>Quantity: <?= (int) $item['quantity']; ?></span>
                                <span class="payment-cart-size">Size: <?= htmlspecialchars($item['size'] ?? 'One Size'); ?></span>
                            </div>
                            <span class="payment-cart-price">$ <?= number_format($item['price'], 2); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="payment-promo">
                    <input type="text" placeholder="Apply Promo Code" aria-label="Promo code">
                    <button type="button">Apply</button>
                </div>

                <hr class="payment-divider">

                <dl class="payment-costs">
                    <div>
                        <dt>Subtotal</dt>
                        <dd>$ <?= number_format($subtotal, 2); ?></dd>
                    </div>
                    <div>
                        <dt>Shipping</dt>
                        <dd>$ <?= number_format($shippingCost, 2); ?></dd>
                    </div>
                    <div>
                        <dt>Taxes (1.8%)</dt>
                        <dd>$ <?= number_format($taxes, 2); ?></dd>
                    </div>
                </dl>

                <hr class="payment-divider">

                <div class="payment-total">
                    <span>Total</span>
                    <strong>$ <?= number_format($total, 2); ?></strong>
                </div>

                <button type="submit" class="payment-checkout-btn">Place Order</button>
            </aside>
        </div>
    </form>
</main>

<?php include 'includes/footer.php'; ?>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="../assets/js/app.js?v=23"></script>
<script>
    (function() {
        const tabs = document.querySelectorAll('.payment-step-tab');
        const panels = document.querySelectorAll('.payment-tab-panel');

        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                const target = tab.getAttribute('aria-controls');

                tabs.forEach((t) => {
                    t.classList.remove('is-active');
                    t.setAttribute('aria-selected', 'false');
                    t.setAttribute('tabindex', '-1');
                });
                panels.forEach((p) => {
                    p.classList.remove('is-active');
                    p.hidden = true;
                });

                tab.classList.add('is-active');
                tab.setAttribute('aria-selected', 'true');
                tab.removeAttribute('tabindex');

                const panel = document.getElementById(target);
                if (panel) {
                    panel.classList.add('is-active');
                    panel.hidden = false;
                }
            });
        });

        const cardNumber = document.getElementById('card-number');
        if (cardNumber) {
            cardNumber.addEventListener('input', (e) => {
                let val = e.target.value.replace(/\D/g, '');
                val = val.slice(0, 16);
                e.target.value = val.replace(/(.{4})/g, '$1 ').trim();
            });
        }

        const cardExpiry = document.getElementById('card-expiry');
        if (cardExpiry) {
            cardExpiry.addEventListener('input', (e) => {
                let val = e.target.value.replace(/\D/g, '');
                val = val.slice(0, 4);
                if (val.length >= 2) {
                    val = val.slice(0, 2) + ' / ' + val.slice(2);
                }
                e.target.value = val;
            });
        }

        const cardCvc = document.getElementById('card-cvc');
        if (cardCvc) {
            cardCvc.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/\D/g, '').slice(0, 4);
            });
        }
    })();
</script>
</body>
</html>
