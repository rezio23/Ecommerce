<?php
require 'includes/security.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In | The DS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Doto:wght@400;600;700;800&family=Krona+One&family=Modak&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css?v=95">
</head>
<body>
    

<?php
$headerId = 'login-top';
$searchId = 'header-login-search';
$bagCount = 0;
$activeButton = 'account';
$currentPage = '';
$searchTrigger = 'button';
$rootPath = '../';
$srcPath = '';
?>

<?php include 'includes/navbar.php'; ?>
<main class="auth-page">
        <div class="auth-card">
            <h1>Log In</h1>
            <hr class="edit-form-divider">
            <form class="auth-form" action="profile.php" method="post">
                <?= csrfField(); ?>
                <div class="edit-form-group">
                    <label class="edit-form-label" for="login-email">Email</label>
                    <input class="edit-form-input" id="login-email" name="email" type="email" placeholder="e.g. sombath@gmail.com" required>
                </div>
                <div class="edit-form-group">
                    <label class="edit-form-label" for="login-password">Password</label>
                    <input class="edit-form-input" id="login-password" name="password" type="password" placeholder="Enter your password" required>
                </div>
                <div class="edit-form-actions">
                    <button type="submit" class="edit-form-button edit-form-button--submit edit-form-button--full">Log In</button>
                </div>
            </form>
            <p class="auth-footer">
                Don't have an account? <a href="signup.php">Create an account</a>
            </p>
        </div>
    </main>

    

<?php include 'includes/footer.php'; ?>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/app.js?v=23"></script>
</body>
</html>
