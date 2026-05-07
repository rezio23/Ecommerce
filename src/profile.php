<?php
require 'includes/security.php';
startSecureSession();
require 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = (int) $_SESSION['user_id'];
$ordered = isset($_GET['ordered']);

$stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
$stmt->execute([':id' => $userId]);
$dbUser = $stmt->fetch();

if (!$dbUser) {
    header('Location: logout.php');
    exit;
}

$profileUser = [
    'name' => $dbUser['full_name'] ?? 'User',
    'handle' => '@' . strtolower(preg_replace('/[^a-z0-9]/', '', (string) $dbUser['full_name'])),
    'email' => $dbUser['email'] ?? '',
    'phone' => $dbUser['phone'] ?: 'Unknown',
    'gender' => $dbUser['gender'] ?: 'Hidden',
    'location' => $dbUser['address'] ?: 'Unknown',
    'avatar' => $dbUser['avatar'] ?: 'https://i1.sndcdn.com/avatars-tDQKBExQks6cE0zh-HO3N7Q-t240x240.jpg',
];

$favStmt = $pdo->prepare('SELECT p.* FROM products p JOIN favorites f ON p.id = f.product_id WHERE f.user_id = :user_id ORDER BY f.created_at DESC');
$favStmt->execute([':user_id' => $userId]);
$favoriteProducts = [];
while ($row = $favStmt->fetch()) {
    $slug = $row['slug'] ?? trim((string) preg_replace('/[^a-z0-9]+/', '-', strtolower($row['name'])), '-');
    $favoriteProducts[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'brand' => $row['brand'],
        'description' => $row['description'],
        'price' => (float) $row['price'],
        'image' => $row['image'],
        'href' => 'product-detail.php?product=' . rawurlencode($slug),
    ];
}

if (empty($favoriteProducts)) {
    $favoriteProducts = [
        [
            'empty' => true,
            'icon' => 'heart',
            'name' => 'No favorites yet',
            'brand' => '',
            'description' => 'Save your favorite items here for quick access.',
            'price' => 0,
            'image' => '',
            'href' => 'shop.php',
        ],
    ];
}

$orderStmt = $pdo->prepare('SELECT o.*, oi.product_name, oi.product_brand, oi.product_price, oi.quantity, oi.size FROM orders o LEFT JOIN order_items oi ON o.id = oi.order_id WHERE o.user_id = :user_id ORDER BY o.created_at DESC');
$orderStmt->execute([':user_id' => $userId]);
$orderProducts = [];
$seenOrders = [];
while ($row = $orderStmt->fetch()) {
    $orderId = $row['id'];
    if (!isset($seenOrders[$orderId])) {
        $seenOrders[$orderId] = true;
        $orderProducts[] = [
            'name' => $row['product_name'] ?? 'Order #' . $orderId,
            'brand' => $row['product_brand'] ?? 'The DS',
            'description' => 'Order total: $' . number_format((float) $row['total'], 2) . ' | Status: ' . ucfirst($row['status']),
            'price' => (float) ($row['product_price'] ?? 0),
            'image' => 'https://cosmeticsbusiness.com/article-image-alias/spider-man-s-tom-holland-swings-into-prada.jpg',
            'href' => 'shop.php',
        ];
    }
}

if (empty($orderProducts)) {
    $orderProducts = [
        [
            'empty' => true,
            'icon' => 'shopping-bag',
            'name' => 'No orders yet',
            'brand' => '',
            'description' => 'Browse the shop and add items to your cart to place your first order.',
            'price' => 0,
            'image' => '',
            'href' => 'shop.php',
        ],
    ];
}

function renderProfileProductCard(array $product): void
{
    if (!empty($product['empty'])) {
        ?>
        <article class="profile-product-card profile-product-card--empty">
            <a class="profile-product-image" href="<?= htmlspecialchars($product['href']); ?>" style="display:flex;align-items:center;justify-content:center;background:#f8f8f8;">
                <i data-lucide="<?= htmlspecialchars($product['icon'] ?? 'shopping-bag'); ?>" style="width:48px;height:48px;color:#aaa;"></i>
            </a>
            <div class="profile-product-meta">
                <p></p>
                <strong></strong>
            </div>
            <h3>
                <a href="<?= htmlspecialchars($product['href']); ?>"><?= htmlspecialchars($product['name']); ?></a>
            </h3>
            <p class="profile-product-copy"><?= htmlspecialchars($product['description']); ?></p>
        </article>
        <?php
        return;
    }
    ?>
    <article class="profile-product-card">
        <a class="profile-product-image" href="<?= htmlspecialchars($product['href']); ?>" aria-label="View <?= htmlspecialchars($product['name']); ?>">
            <img src="<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
        </a>
        <div class="profile-product-meta">
            <p><?= htmlspecialchars($product['brand']); ?></p>
            <strong>$ <?= number_format((float) $product['price'], 2); ?></strong>
        </div>
        <h3>
            <a href="<?= htmlspecialchars($product['href']); ?>"><?= htmlspecialchars($product['name']); ?></a>
        </h3>
        <p class="profile-product-copy"><?= htmlspecialchars($product['description']); ?></p>
    </article>
    <?php
}

function renderProfileProductSection(string $title, array $products, string $label): void
{
    $panelId = $label . '-panel';
    ?>
    <section class="profile-products-section" data-profile-section aria-labelledby="<?= htmlspecialchars($label); ?>">
        <div class="profile-section-heading">
            <button
                class="profile-panel-toggle"
                id="<?= htmlspecialchars($label); ?>"
                type="button"
                data-product-toggle
                aria-expanded="true"
                aria-controls="<?= htmlspecialchars($panelId); ?>"
            >
                <span><?= htmlspecialchars($title); ?></span>
                <i data-lucide="chevron-down" aria-hidden="true"></i>
            </button>
            <div class="profile-section-controls" aria-label="<?= htmlspecialchars($title); ?> controls">
                <button type="button" data-profile-carousel-prev aria-label="Previous <?= htmlspecialchars($title); ?>">
                    <i data-lucide="chevron-left"></i>
                </button>
                <button type="button" data-profile-carousel-next aria-label="Next <?= htmlspecialchars($title); ?>">
                    <i data-lucide="chevron-right"></i>
                </button>
            </div>
        </div>

        <div class="profile-product-viewport product-panel-content profile-panel-content" id="<?= htmlspecialchars($panelId); ?>" data-profile-carousel-panel>
            <div class="profile-product-grid" data-profile-carousel>
                <?php foreach ($products as $product): ?>
                    <?php renderProfileProductCard($product); ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | The DS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Doto:wght@400;600;700;800&family=Krona+One&family=Modak&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css?v=92">
</head>
<body class="profile-page">
    

<?php
$headerId = 'profile-top';
$searchId = 'header-profile-search';
$bagCount = 0;
$activeButton = 'profile';
$currentPage = 'shop';
$searchTrigger = 'button';
$rootPath = '../';
$srcPath = '';
?>

<?php include 'includes/navbar.php'; ?>
<main class="profile-main">
    <?php if ($ordered): ?>
        <div class="form-success" style="grid-column: 1 / -1; max-width: 800px; margin: 0 auto 1rem; color: #070; background: #eaffea; padding: 1rem; border-radius: 8px; text-align: center;">
            <p>Your order has been placed successfully!</p>
        </div>
    <?php endif; ?>
        <aside class="profile-sidebar" aria-label="Profile summary">
            <div class="profile-card">
                <img class="profile-avatar" src="<?= htmlspecialchars($profileUser['avatar']); ?>" alt="<?= htmlspecialchars($profileUser['name']); ?>">
                <h1><?= htmlspecialchars($profileUser['name']); ?></h1>
                <p><?= htmlspecialchars($profileUser['handle']); ?></p>
                <dl class="profile-detail-list">
                    <div>
                        <dt>Email</dt>
                        <dd><?= htmlspecialchars($profileUser['email']); ?></dd>
                    </div>
                    <div>
                        <dt>Gender</dt>
                        <dd><?= htmlspecialchars($profileUser['gender']); ?></dd>
                    </div>
                    <div>
                        <dt>Phone</dt>
                        <dd><?= htmlspecialchars($profileUser['phone']); ?></dd>
                    </div>
                    <div>
                        <dt>Location</dt>
                        <dd><?= htmlspecialchars($profileUser['location']); ?></dd>
                    </div>
                </dl>
                <a href="edit-profile.php" class="profile-edit-link" data-edit-open>Edit Profile</a>
            </div>

            <nav class="profile-footer-links" aria-label="Profile actions">
                <a href="terms.php">Terms &amp; Conditions</a>
                <span aria-hidden="true"></span>
                <a href="logout.php" data-logout>Logout</a>
            </nav>
        </aside>

        <div class="profile-content">
            <?php renderProfileProductSection('Your Order', $orderProducts, 'profile-orders-title'); ?>
            <?php renderProfileProductSection('Your Favorite', $favoriteProducts, 'profile-favorites-title'); ?>
        </div>
    </main>

    

<?php include 'includes/footer.php'; ?>
<div class="edit-profile-overlay" id="edit-profile-modal" aria-hidden="true">
        <div class="edit-profile-modal" role="dialog" aria-modal="true" aria-labelledby="edit-profile-title">
            <button type="button" class="edit-profile-close" data-edit-close aria-label="Close edit profile">
                <i data-lucide="x"></i>
            </button>
            <h2 id="edit-profile-title">Edit Personal Detail</h2>
            <hr class="edit-form-divider">
            <form class="edit-form" action="profile.php" method="post">
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
                    <button type="button" class="edit-form-button edit-form-button--cancel" data-edit-close>Cancel</button>
                    <button type="submit" class="edit-form-button edit-form-button--submit">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/app.js?v=23"></script>
    <div class="confirm-overlay" id="logout-confirm" aria-hidden="true">
        <div class="confirm-modal" role="alertdialog" aria-modal="true" aria-labelledby="logout-confirm-title" aria-describedby="logout-confirm-desc">
            <h2 id="logout-confirm-title">Log Out</h2>
            <p id="logout-confirm-desc">Are you sure you want to log out?</p>
            <div class="confirm-actions">
                <button type="button" class="confirm-btn confirm-btn--cancel" data-logout-cancel>Cancel</button>
                <a href="login.php" class="confirm-btn confirm-btn--confirm">Log Out</a>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const logoutLink = document.querySelector('[data-logout]');
            const overlay = document.getElementById('logout-confirm');
            const cancelBtn = document.querySelector('[data-logout-cancel]');

            if (logoutLink && overlay) {
                logoutLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    overlay.classList.add('is-open');
                    overlay.setAttribute('aria-hidden', 'false');
                    cancelBtn.focus();
                });
            }

            if (cancelBtn && overlay) {
                cancelBtn.addEventListener('click', function() {
                    overlay.classList.remove('is-open');
                    overlay.setAttribute('aria-hidden', 'true');
                });
            }

            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) {
                    overlay.classList.remove('is-open');
                    overlay.setAttribute('aria-hidden', 'true');
                }
            });
        })();
    </script>
</body>
</html>
