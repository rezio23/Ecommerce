<?php
require __DIR__ . '/data.php';
require __DIR__ . '/includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | <?= htmlspecialchars($site['brand']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php require __DIR__ . '/includes/header.php'; ?>

    <main>
        <section class="auth-section my-5 py-5">
            <div class="container text-center mt-3 pt-5">
                <h2 class="auth-title">Login</h2>
                <hr class="auth-divider">
            </div>

            <div class="auth-form-wrap mx-auto container">
                <form id="login-form" action="login.php" method="post">
                    <div class="form-group">
                        <label for="login-email">Email</label>
                        <input type="text" class="field-control" id="login-email" name="email" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label for="login-password">Password</label>
                        <input type="password" class="field-control" id="login-password" name="password" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <input type="submit" class="primary-btn full-btn" id="login-btn" value="Login">
                    </div>
                    <div class="form-group text-center">
                        <a href="register.php" class="auth-link">Don't have account? Register</a>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
