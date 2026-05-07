<?php
require 'includes/security.php';
require 'includes/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();

    $fullName = getPost('full_name');
    $email = getPost('email');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (strlen($fullName) < 2) {
        $errors[] = 'Full name must be at least 2 characters.';
    }
    if (!validateEmail($email)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }
    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        $check = $pdo->prepare('SELECT id FROM users WHERE email = :email');
        $check->execute([':email' => $email]);
        if ($check->fetch()) {
            $errors[] = 'An account with this email already exists.';
        }
    }

    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (full_name, email, password_hash) VALUES (:full_name, :email, :password_hash)');
        $stmt->execute([
            ':full_name' => $fullName,
            ':email' => $email,
            ':password_hash' => $hash,
        ]);
        header('Location: login.php?registered=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | The DS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Doto:wght@400;600;700;800&family=Krona+One&family=Modak&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css?v=95">
</head>
<body>
    

<?php
$headerId = 'signup-top';
$searchId = 'header-signup-search';
$bagCount = 0;
$activeButton = '';
$currentPage = '';
$searchTrigger = 'button';
$rootPath = '../';
$srcPath = '';
?>

<?php include 'includes/navbar.php'; ?>
<main class="auth-page">
        <div class="auth-card">
            <h1>Sign Up</h1>
            <hr class="edit-form-divider">
            <form class="auth-form" action="signup.php" method="post">
                <?= csrfField(); ?>
                <?php if (!empty($errors)): ?>
                    <div class="auth-errors" style="color: #e63946; margin-bottom: 1rem; font-size: 0.9rem;">
                        <?php foreach ($errors as $error): ?>
                            <p style="margin: 0.25rem 0;"><?= htmlspecialchars($error); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div class="edit-form-group">
                    <label class="edit-form-label" for="signup-full-name">Full name</label>
                    <input class="edit-form-input" id="signup-full-name" name="full_name" type="text" placeholder="e.g. John Smith" required>
                </div>
                <div class="edit-form-group">
                    <label class="edit-form-label" for="signup-email">Email</label>
                    <input class="edit-form-input" id="signup-email" name="email" type="email" placeholder="e.g. sombath@gmail.com" required>
                </div>
                <div class="edit-form-group">
                    <label class="edit-form-label" for="signup-password">Password</label>
                    <input class="edit-form-input" id="signup-password" name="password" type="password" placeholder="Create a password" required>
                </div>
                <div class="edit-form-group">
                    <label class="edit-form-label" for="signup-confirm-password">Confirm Password</label>
                    <input class="edit-form-input" id="signup-confirm-password" name="confirm_password" type="password" placeholder="Confirm your password" required>
                </div>
                <div class="edit-form-actions">
                    <button type="submit" class="edit-form-button edit-form-button--submit edit-form-button--full">Sign Up</button>
                </div>
            </form>
            <p class="auth-footer">
                Already have an account? <a href="login.php">Log in</a>
            </p>
        </div>
    </main>

    

<?php include 'includes/footer.php'; ?>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/app.js?v=23"></script>
</body>
</html>
