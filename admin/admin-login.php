<?php
require '../includes/security.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();

    $email = getPost('email');
    $password = $_POST['password'] ?? '';

    if ($email === 'admin@gmail.com' && $password === 'Admin123') {
        startSecureSession();
        $_SESSION['admin'] = true;
        $_SESSION['admin_email'] = $email;
        header('Location: admin-dashboard.php');
        exit;
    } else {
        $errors[] = 'Invalid admin email or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | The DS</title>
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
$srcPath = '../pages/';
?>

<?php include '../includes/navbar.php'; ?>
<main class="auth-page">
    <div class="auth-card">
        <h1>Admin Login</h1>
        <hr class="edit-form-divider">
        <form class="auth-form" action="admin-login.php" method="post">
            <?= csrfField(); ?>
            <?php if (!empty($errors)): ?>
                <div class="auth-errors" style="color: #e63946; margin-bottom: 1rem; font-size: 0.9rem;">
                    <?php foreach ($errors as $error): ?>
                        <p style="margin: 0.25rem 0;"><?= htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="edit-form-group">
                <label class="edit-form-label" for="admin-email">Email</label>
                <input class="edit-form-input" id="admin-email" name="email" type="email" placeholder="admin@gmail.com" required>
            </div>
            <div class="edit-form-group">
                <label class="edit-form-label" for="admin-password">Password</label>
                <input class="edit-form-input" id="admin-password" name="password" type="password" placeholder="Enter admin password" required>
            </div>
            <div class="edit-form-actions">
                <button type="submit" class="edit-form-button edit-form-button--submit edit-form-button--full">Log In</button>
            </div>
        </form>
        <p class="auth-footer">
            <a href="../pages/login.php">User Login</a>
        </p>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="../assets/js/app.js?v=23"></script>
</body>
</html>
