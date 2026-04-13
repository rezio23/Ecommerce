<?php
require __DIR__ . '/data.php';
require __DIR__ . '/includes/functions.php';

$cartItems = getCartItems($products);
$subtotal  = getCartSubtotal($products);
$total     = $subtotal;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Out | <?= htmlspecialchars($site['brand']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php require __DIR__ . '/includes/header.php'; ?>

    <main>
        <section class="my-5 py-5">
            <div class="container text-center mt-3 pt-5">
                <h2 class="auth-title">Check Out</h2>
                <hr class="auth-divider">
            </div>

            <div class="mx-auto container">
                <form id="checkout-form" action="checkout.php" method="post">
                    <div class="checkout-row">
                        <div class="checkout-small-element">
                            <label for="checkout-name">Name</label>
                            <input type="text" class="field-control" id="checkout-name" name="name" placeholder="Name">
                        </div>
                        <div class="checkout-small-element">
                            <label for="checkout-email">Email</label>
                            <input type="text" class="field-control" id="checkout-email" name="email" placeholder="Email">
                        </div>
                    </div>

                    <div class="checkout-row">
                        <div class="checkout-small-element">
                            <label for="checkout-phone">Phone</label>
                            <input type="tel" class="field-control" id="checkout-phone" name="phone" placeholder="Phone">
                        </div>
                        <div class="checkout-small-element">
                            <label for="checkout-city">City</label>
                            <input type="text" class="field-control" id="checkout-city" name="city" placeholder="City">
                        </div>
                    </div>

                    <div class="form-group checkout-large-element">
                        <label for="checkout-address">Address</label>
                        <input type="text" class="field-control" id="checkout-address" name="address" placeholder="Address">
                    </div>

                    <div class="checkout-btn-container">
                        <input type="submit" class="primary-btn checkout-btn" id="checkout-btn" value="Checkout">
                    </div>

                    <div class="form-group">
                        <a href="login.php" id="login-url" class="auth-link">Do you have an account? Login</a>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
