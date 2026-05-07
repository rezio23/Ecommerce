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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();

    $fullName = getPost('full_name');
    $gender = getPost('gender');
    $address = getPost('address');
    $phone = getPost('phone');

    $avatar = null;
    if (!empty($_FILES['profile_picture']['tmp_name'])) {
        $result = validateFileUpload($_FILES['profile_picture'], ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], 2 * 1024 * 1024);
        if ($result['ok']) {
            $avatar = 'uploads/' . $result['name'];
        } else {
            $errors[] = $result['error'];
        }
    }

    if (empty($errors)) {
        if ($avatar) {
            $stmt = $pdo->prepare('UPDATE users SET full_name = :full_name, gender = :gender, address = :address, phone = :phone, avatar = :avatar WHERE id = :id');
            $stmt->execute([
                ':full_name' => $fullName,
                ':gender' => $gender,
                ':address' => $address,
                ':phone' => $phone,
                ':avatar' => $avatar,
                ':id' => $userId,
            ]);
        } else {
            $stmt = $pdo->prepare('UPDATE users SET full_name = :full_name, gender = :gender, address = :address, phone = :phone WHERE id = :id');
            $stmt->execute([
                ':full_name' => $fullName,
                ':gender' => $gender,
                ':address' => $address,
                ':phone' => $phone,
                ':id' => $userId,
            ]);
        }
        $_SESSION['user_name'] = $fullName;
        header('Location: profile.php');
        exit;
    }
}

$stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
$stmt->execute([':id' => $userId]);
$dbUser = $stmt->fetch();

$profileUser = [
    'name' => $dbUser['full_name'] ?? 'User',
    'handle' => '@' . strtolower(preg_replace('/[^a-z0-9]/', '', (string) $dbUser['full_name'])),
    'email' => $dbUser['email'] ?? '',
    'phone' => $dbUser['phone'] ?: 'Unknown',
    'gender' => $dbUser['gender'] ?: 'Hidden',
    'location' => $dbUser['address'] ?: 'Unknown',
    'avatar' => $dbUser['avatar'] ?: 'https://i1.sndcdn.com/avatars-tDQKBExQks6cE0zh-HO3N7Q-t240x240.jpg',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | The DS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Doto:wght@400;600;700;800&family=Krona+One&family=Modak&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css?v=92">
</head>
<body>
    

<?php
$headerId = 'edit-top';
$searchId = 'header-edit-search';
$bagCount = 0;
$activeButton = 'profile';
$currentPage = '';
$searchTrigger = 'button';
$rootPath = '../';
$srcPath = '';
?>

<?php include 'includes/navbar.php'; ?>
<main class="edit-profile-page">
        <div class="edit-profile-card">
            <h1>Edit Personal Detail</h1>
            <hr class="edit-form-divider">
            <form class="edit-form" action="profile.php" method="post" enctype="multipart/form-data">
                <?= csrfField(); ?>
                <div class="edit-form-group">
                    <label class="edit-form-label" for="edit-full-name">Full name</label>
                    <input class="edit-form-input" id="edit-full-name" name="full_name" type="text" value="<?= htmlspecialchars($profileUser['name']); ?>" placeholder="e.g. John Smith">
                </div>
                <div class="edit-form-group">
                    <label class="edit-form-label" for="edit-username">Username</label>
                    <input class="edit-form-input" id="edit-username" name="username" type="text" value="<?= htmlspecialchars($profileUser['handle']); ?>" placeholder="e.g. johnsmith123">
                </div>
                <div class="edit-form-group">
                    <label class="edit-form-label" for="edit-gender">Gender</label>
                    <select class="edit-form-input edit-form-select" id="edit-gender" name="gender">
                        <option value="Hidden" <?= $profileUser['gender'] === 'Hidden' ? 'selected' : ''; ?>>Hidden</option>
                        <option value="Male" <?= $profileUser['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?= $profileUser['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?= $profileUser['gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <div class="edit-form-group">
                    <label class="edit-form-label" for="edit-profile-pic">Profile Picture</label>
                    <div class="edit-form-file-wrap">
                        <span class="edit-form-file-text" id="edit-file-text">Browser File</span>
                        <input id="edit-profile-pic" name="profile_picture" type="file" accept="image/*" aria-label="Profile picture">
                    </div>
                </div>
                <div class="edit-form-group">
                    <label class="edit-form-label" for="edit-address">Address</label>
                    <input class="edit-form-input" id="edit-address" name="address" type="text" value="<?= htmlspecialchars($profileUser['location']); ?>" placeholder="e.g. Toul Kork, Cambodia">
                </div>
                <div class="edit-form-group">
                    <label class="edit-form-label" for="edit-phone">Phone</label>
                    <input class="edit-form-input" id="edit-phone" name="phone" type="tel" value="<?= $profileUser['phone'] !== 'Unknown' ? htmlspecialchars($profileUser['phone']) : ''; ?>" placeholder="e.g. 85511 223 344">
                </div>
                <div class="edit-form-actions">
                    <a href="profile.php" class="edit-form-button edit-form-button--cancel" role="button">Cancel</a>
                    <button type="submit" class="edit-form-button edit-form-button--submit">Submit</button>
                </div>
            </form>
        </div>
    </main>

    

<?php include 'includes/footer.php'; ?>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/app.js?v=22"></script>
    <script>
        const fileInput = document.getElementById('edit-profile-pic');
        const fileText = document.getElementById('edit-file-text');
        if (fileInput && fileText) {
            fileInput.addEventListener('change', () => {
                fileText.textContent = fileInput.files[0]?.name || 'Browser File';
            });
        }
    </script>
</body>
</html>
