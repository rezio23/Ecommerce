<?php
require __DIR__ . '/data.php';
require __DIR__ . '/includes/functions.php';

// Sample static account data (as shown in the videos)
$accountUser = [
    'name' => 'Sombath',
    'email' => 'sombath@email.com',
];

// Sample orders data (as shown in video 49)
$orders = [
    [
        'product' => [
            'name'  => 'Classic White Sneakers',
            'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=80&q=80',
        ],
        'date' => '2026-4-9',
    ],
    [
        'product' => [
            'name'  => 'Mint Travel Backpack',
            'image' => 'https://images.unsplash.com/photo-1581605405669-fcdf81165afa?auto=format&fit=crop&w=80&q=80',
        ],
        'date' => '2026-4-9',
    ],
];

// Determine which section to show
$showOrders = isset($_GET['section']) && $_GET['section'] === 'orders';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account | <?= htmlspecialchars($site['brand']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php require __DIR__ . '/includes/header.php'; ?>

    <main>
        <?php if ($showOrders): ?>
        <!-- Orders Section -->
        <section class="orders container my-5 py-5">
            <div class="container text-center mt-3 pt-5">
                <h2 class="auth-title">Your Orders</h2>
                <hr class="auth-divider">
            </div>

            <table class="mt-5 pt-5">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>
                            <div class="product-info">
                                <img src="<?= htmlspecialchars($order['product']['image']) ?>" alt="<?= htmlspecialchars($order['product']['name']) ?>">
                                <div>
                                    <p class="mt-3"><?= htmlspecialchars($order['product']['name']) ?></p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span><?= htmlspecialchars($order['date']) ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <?php else: ?>
        <!-- Account Info + Change Password -->
        <section class="my-5 py-5">
            <div class="row container mx-auto">
                <!-- Left: Account Info -->
                <div class="account-info-col text-center mt-3 pt-5 col-lg-6 col-md-12 col-sm-12">
                    <h3 class="font-weight-bold">Account info</h3>
                    <hr class="auth-divider mx-auto">
                    <div id="account-info" class="account-info">
                        <p>Name: <span><?= htmlspecialchars($accountUser['name']) ?></span></p>
                        <p>Email: <span><?= htmlspecialchars($accountUser['email']) ?></span></p>
                        <p><a href="account.php?section=orders" id="order-btn">Your orders</a></p>
                        <p><a href="login.php" id="logout-btn">Logout</a></p>
                    </div>
                </div>

                <!-- Right: Change Password -->
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <form id="account-form" action="account.php" method="post">
                        <h3>Change Password</h3>
                        <hr class="auth-divider mx-auto">
                        <div class="form-group">
                            <label for="account-password">Password</label>
                            <input type="password" class="field-control" id="account-password" name="password" placeholder="New Password">
                        </div>
                        <div class="form-group">
                            <label for="account-confirm-password">Confirm Password</label>
                            <input type="password" class="field-control" id="account-confirm-password" name="confirmPassword" placeholder="Confirm Password">
                        </div>
                        <div class="form-group">
                            <input type="submit" class="primary-btn" id="change-pass-btn" value="Change Password">
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <?php endif; ?>
    </main>

    <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
