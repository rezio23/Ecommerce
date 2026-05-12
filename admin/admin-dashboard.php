<?php
require '../includes/security.php';
require '../includes/db.php';

// Helper: send plain-text email with optional file attachment via MIME multipart
function sendEmailWithAttachment(string $to, string $subject, string $bodyText, string $from, ?string $filePath = null, ?string $fileName = null): bool {
    $boundary = md5(uniqid('', true));
    $headers = "From: {$from}\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";

    $body = "--{$boundary}\r\n";
    $body .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";
    $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $body .= $bodyText . "\r\n\r\n";

    if ($filePath && is_file($filePath) && is_readable($filePath)) {
        $data = file_get_contents($filePath);
        if ($data !== false) {
            $encoded = chunk_split(base64_encode($data));
            $mime = mime_content_type($filePath) ?: 'application/octet-stream';
            $safeName = basename($fileName ?: basename($filePath));
            $body .= "--{$boundary}\r\n";
            $body .= "Content-Type: {$mime}; name=\"{$safeName}\"\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n";
            $body .= "Content-Disposition: attachment; filename=\"{$safeName}\"\r\n\r\n";
            $body .= $encoded . "\r\n\r\n";
        }
    }

    $body .= "--{$boundary}--";
    return mail($to, $subject, $body, $headers);
}

startSecureSession();

if (empty($_SESSION['admin'])) {
    header('Location: admin-login.php');
    exit;
}

// Stats
$totalUsers = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$totalOrders = (int) $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$totalProducts = (int) $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
$totalRevenue = (float) $pdo->query("SELECT COALESCE(SUM(total), 0) FROM orders WHERE status != 'cancelled'")->fetchColumn();

// Monthly revenue for chart
$monthlyRevenueStmt = $pdo->query("SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, COALESCE(SUM(total), 0) AS revenue FROM orders WHERE status != 'cancelled' GROUP BY month ORDER BY month");
$monthlyRevenue = $monthlyRevenueStmt->fetchAll();

// Recent orders
$recentOrdersStmt = $pdo->query('SELECT o.*, u.full_name AS user_name FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 10');
$recentOrders = $recentOrdersStmt->fetchAll();

// Recent users
$recentUsersStmt = $pdo->query('SELECT id, full_name, email, created_at FROM users ORDER BY created_at DESC LIMIT 10');
$recentUsers = $recentUsersStmt->fetchAll();

// All categories
$categoriesStmt = $pdo->query('SELECT * FROM categories ORDER BY name');
$allCategories = $categoriesStmt->fetchAll();

// All products for management (with optional category filter + sort)
$selectedCategory = $_GET['category'] ?? '';
$selectedSort = $_GET['sort'] ?? 'number_desc';

$orderBy = match ($selectedSort) {
    'number_asc' => 'p.id ASC',
    'number_desc' => 'p.id DESC',
    'name_asc' => 'p.name ASC',
    'name_desc' => 'p.name DESC',
    'price_asc' => 'p.price ASC',
    'price_desc' => 'p.price DESC',
    default => 'p.id DESC',
};

$sortLabels = [
    'number_desc' => 'Number',
    'number_asc' => 'Number',
    'name_asc' => 'Name (A-Z)',
    'name_desc' => 'Name (Z-A)',
    'price_asc' => 'Price (Low to High)',
    'price_desc' => 'Price (High to Low)',
];
$currentSortLabel = $sortLabels[$selectedSort] ?? 'Number';

if ($selectedCategory !== '') {
    $productsStmt = $pdo->prepare("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category = c.slug WHERE p.category = :category ORDER BY {$orderBy}");
    $productsStmt->execute([':category' => $selectedCategory]);
} else {
    $productsStmt = $pdo->query("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category = c.slug ORDER BY {$orderBy}");
}
$allProducts = $productsStmt->fetchAll();

// All orders for management
$allOrdersStmt = $pdo->query('SELECT o.*, u.full_name AS user_name FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC');
$allOrders = $allOrdersStmt->fetchAll();

// All user requests for management
$requestsStmt = $pdo->query('SELECT r.*, u.phone AS user_phone FROM user_requests r LEFT JOIN users u ON r.user_id = u.id ORDER BY r.created_at DESC');
$allRequests = $requestsStmt->fetchAll();

// Handle order status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_order_status'])) {
    requireCsrf();
    $orderId = (int) ($_POST['order_id'] ?? 0);
    $newStatus = getPost('status');
    $allowedStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
    if ($orderId > 0 && in_array($newStatus, $allowedStatuses, true)) {
        $stmt = $pdo->prepare('UPDATE orders SET status = :status WHERE id = :id');
        $stmt->execute([':status' => $newStatus, ':id' => $orderId]);
    }
    header('Location: admin-dashboard.php?tab=orders');
    exit;
}

// Handle product add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    requireCsrf();
    $name = getPost('name');
    $brand = getPost('brand');
    $description = getPost('description');
    $price = (float) ($_POST['price'] ?? 0);
    $category = getPost('category');
    $tags = getPost('tags');
    $badge = getPost('badge');
    $rating = getPost('rating');
    $image = getPost('image');
    $gallery = getPost('gallery');

    if ($name !== '' && $brand !== '' && $price > 0 && $image !== '') {
        $slug = trim((string) preg_replace('/[^a-z0-9]+/', '-', strtolower($name)), '-');
        $stmt = $pdo->prepare(
            'INSERT INTO products (slug, name, brand, description, price, tags, rating, badge, image, gallery, category, created_at)
             VALUES (:slug, :name, :brand, :description, :price, :tags, :rating, :badge, :image, :gallery, :category, NOW())'
        );
        $stmt->execute([
            ':slug' => $slug,
            ':name' => $name,
            ':brand' => $brand,
            ':description' => $description,
            ':price' => $price,
            ':tags' => $tags,
            ':rating' => $rating,
            ':badge' => $badge,
            ':image' => $image,
            ':gallery' => $gallery,
            ':category' => $category,
        ]);
    }
    header('Location: admin-dashboard.php?tab=products');
    exit;
}

// Handle product delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    requireCsrf();
    $productId = (int) ($_POST['product_id'] ?? 0);
    if ($productId > 0) {
        $stmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
        $stmt->execute([':id' => $productId]);
    }
    header('Location: admin-dashboard.php?tab=products');
    exit;
}

// Handle product stock update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_stock'])) {
    requireCsrf();
    $productId = (int) ($_POST['product_id'] ?? 0);
    $newStock = (int) ($_POST['stock'] ?? 0);
    if ($productId > 0) {
        $stmt = $pdo->prepare('UPDATE products SET stock = :stock WHERE id = :id');
        $stmt->execute([':stock' => $newStock, ':id' => $productId]);
    }
    header('Location: admin-dashboard.php?tab=products');
    exit;
}

// Handle product edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_product'])) {
    requireCsrf();
    $productId = (int) ($_POST['product_id'] ?? 0);
    $name = getPost('name');
    $brand = getPost('brand');
    $description = getPost('description');
    $price = (float) ($_POST['price'] ?? 0);
    $stock = (int) ($_POST['stock'] ?? 0);
    $category = getPost('category');
    $tags = getPost('tags');
    $badge = getPost('badge');
    $rating = getPost('rating');
    $image = getPost('image');
    $gallery = getPost('gallery');

    if ($productId > 0 && $name !== '' && $brand !== '' && $price > 0 && $image !== '') {
        $slug = trim((string) preg_replace('/[^a-z0-9]+/', '-', strtolower($name)), '-');
        $stmt = $pdo->prepare(
            'UPDATE products SET slug = :slug, name = :name, brand = :brand, description = :description, price = :price, stock = :stock, category = :category, tags = :tags, badge = :badge, rating = :rating, image = :image, gallery = :gallery WHERE id = :id'
        );
        $stmt->execute([
            ':slug' => $slug,
            ':name' => $name,
            ':brand' => $brand,
            ':description' => $description,
            ':price' => $price,
            ':stock' => $stock,
            ':category' => $category,
            ':tags' => $tags,
            ':badge' => $badge,
            ':rating' => $rating,
            ':image' => $image,
            ':gallery' => $gallery,
            ':id' => $productId,
        ]);
    }
    header('Location: admin-dashboard.php?tab=products');
    exit;
}

// Handle category add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    requireCsrf();
    $catName = getPost('category_name');
    if ($catName !== '') {
        $catSlug = trim((string) preg_replace('/[^a-z0-9]+/', '-', strtolower($catName)), '-');
        $stmt = $pdo->prepare('INSERT INTO categories (name, slug) VALUES (:name, :slug) ON DUPLICATE KEY UPDATE name = :name');
        $stmt->execute([':name' => $catName, ':slug' => $catSlug]);
    }
    header('Location: admin-dashboard.php?tab=products');
    exit;
}

// Handle category delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_category'])) {
    requireCsrf();
    $catId = (int) ($_POST['category_id'] ?? 0);
    if ($catId > 0) {
        $stmt = $pdo->prepare('DELETE FROM categories WHERE id = :id');
        $stmt->execute([':id' => $catId]);
    }
    header('Location: admin-dashboard.php?tab=products');
    exit;
}

// Handle request status update (accept / reject)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_request_status'])) {
    requireCsrf();
    $requestId = (int) ($_POST['request_id'] ?? 0);
    $newStatus = getPost('status');
    $allowedStatuses = ['pending', 'accepted', 'rejected'];
    if ($requestId > 0 && in_array($newStatus, $allowedStatuses, true)) {
        $stmt = $pdo->prepare('UPDATE user_requests SET status = :status WHERE id = :id');
        $stmt->execute([':status' => $newStatus, ':id' => $requestId]);
    }
    header('Location: admin-dashboard.php?tab=requests');
    exit;
}

// Handle announcement broadcast
$announcementStatus = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_announcement'])) {
    requireCsrf();
    $subject = getPost('subject');
    $message = getPost('message');

    if ($subject === '' || $message === '') {
        $announcementStatus = 'error_empty';
    } else {
        $filePath = null;
        $fileName = null;
        if (!empty($_FILES['attachment']['tmp_name'])) {
            $result = validateFileUpload(
                $_FILES['attachment'],
                ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf'],
                5 * 1024 * 1024
            );
            if ($result['ok']) {
                $filePath = $result['path'];
                $fileName = $result['name'];
            } else {
                $announcementStatus = 'error_upload:' . $result['error'];
            }
        }

        if ($announcementStatus === '' || !str_starts_with($announcementStatus, 'error_')) {
            // Fetch all user emails
            $emailsStmt = $pdo->query('SELECT email FROM users WHERE email IS NOT NULL AND email != "" ORDER BY id');
            $emails = $emailsStmt->fetchAll(PDO::FETCH_COLUMN);
            $sentCount = 0;
            $from = 'The DS Store <thedaservice@store.com>';

            foreach ($emails as $email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $ok = sendEmailWithAttachment($email, $subject, $message, $from, $filePath, $fileName);
                    if ($ok) {
                        $sentCount++;
                    }
                }
            }

            // Save record
            $jsonPath = realpath(__DIR__ . '/../uploads/support/announcements.json') ?: __DIR__ . '/../uploads/support/announcements.json';
            $records = [];
            if (is_file($jsonPath)) {
                $content = file_get_contents($jsonPath);
                if ($content !== false) {
                    $decoded = json_decode($content, true);
                    if (is_array($decoded)) {
                        $records = $decoded;
                    }
                }
            }
            $dir = dirname($jsonPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0750, true);
            }
            $records[] = [
                'id' => 'ann_' . uniqid('', true),
                'subject' => $subject,
                'message' => $message,
                'file' => $fileName ?: '',
                'sent_count' => $sentCount,
                'total_users' => count($emails),
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $tmpFile = $dir . '/announcements.tmp.' . bin2hex(random_bytes(8)) . '.json';
            file_put_contents($tmpFile, json_encode($records, JSON_PRETTY_PRINT));
            rename($tmpFile, $jsonPath);

            $announcementStatus = 'success:' . $sentCount;
        }
    }

    header('Location: admin-dashboard.php?tab=announcements&status=' . urlencode($announcementStatus));
    exit;
}

$activeTab = $_GET['tab'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | The DS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Doto:wght@400;600;700;800&family=Krona+One&family=Modak&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css?v=106">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
</head>
<body class="admin-body">

<div class="admin-layout">
    <aside class="admin-sidebar">
        <div class="admin-brand">
            <a href="../index.php">the DS</a>
            <span class="admin-badge">Admin</span>
        </div>
        <nav class="admin-nav">
            <a href="?tab=dashboard" class="admin-nav-link <?= $activeTab === 'dashboard' ? 'is-active' : ''; ?>">
                <i data-lucide="layout-dashboard"></i> Dashboard
            </a>
            <a href="?tab=orders" class="admin-nav-link <?= $activeTab === 'orders' ? 'is-active' : ''; ?>">
                <i data-lucide="shopping-cart"></i> Orders
            </a>
            <a href="?tab=products" class="admin-nav-link <?= $activeTab === 'products' ? 'is-active' : ''; ?>">
                <i data-lucide="package"></i> Products
            </a>
            <a href="?tab=users" class="admin-nav-link <?= $activeTab === 'users' ? 'is-active' : ''; ?>">
                <i data-lucide="users"></i> Users
            </a>
            <a href="?tab=requests" class="admin-nav-link <?= $activeTab === 'requests' ? 'is-active' : ''; ?>">
                <i data-lucide="inbox"></i> User Requests
            </a>
            <a href="?tab=announcements" class="admin-nav-link <?= $activeTab === 'announcements' ? 'is-active' : ''; ?>">
                <i data-lucide="megaphone"></i> Announcements
            </a>
        <div class="admin-sidebar-footer">
            <span class="admin-email"><?= htmlspecialchars($_SESSION['admin_email'] ?? ''); ?></span>
            <a href="admin-logout.php" class="admin-logout">
                <i data-lucide="log-out"></i> Log Out
            </a>
        </div>
    </aside>

    <main class="admin-main">
        <?php if ($activeTab === 'dashboard'): ?>
            <div class="admin-header">
                <h1>Dashboard</h1>
            </div>

            <div class="admin-stats">
                <div class="admin-stat-card">
                    <div class="admin-stat-icon"><i data-lucide="users"></i></div>
                    <div class="admin-stat-info">
                        <span class="admin-stat-value"><?= number_format($totalUsers); ?></span>
                        <span class="admin-stat-label">Total Users</span>
                    </div>
                </div>
                <div class="admin-stat-card">
                    <div class="admin-stat-icon"><i data-lucide="shopping-bag"></i></div>
                    <div class="admin-stat-info">
                        <span class="admin-stat-value"><?= number_format($totalOrders); ?></span>
                        <span class="admin-stat-label">Total Orders</span>
                    </div>
                </div>
                <div class="admin-stat-card">
                    <div class="admin-stat-icon"><i data-lucide="package"></i></div>
                    <div class="admin-stat-info">
                        <span class="admin-stat-value"><?= number_format($totalProducts); ?></span>
                        <span class="admin-stat-label">Total Products</span>
                    </div>
                </div>
                <div class="admin-stat-card">
                    <div class="admin-stat-icon"><i data-lucide="dollar-sign"></i></div>
                    <div class="admin-stat-info">
                        <span class="admin-stat-value">$<?= number_format($totalRevenue, 2); ?></span>
                        <span class="admin-stat-label">Total Revenue</span>
                    </div>
                </div>
            </div>

            <div class="admin-section admin-chart-section">
                <h2>Monthly Revenue</h2>
                <div class="admin-chart-wrap">
                    <?php if (empty($monthlyRevenue)): ?>
                        <div class="admin-chart-empty">No revenue data yet.</div>
                    <?php else: ?>
                        <canvas id="revenueChart"></canvas>
                    <?php endif; ?>
                </div>
            </div>

            <div class="admin-sections">
                <div class="admin-section">
                    <h2>Recent Orders</h2>
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentOrders as $order): ?>
                                    <tr>
                                        <td>#<?= (int) $order['id']; ?></td>
                                        <td><?= htmlspecialchars($order['user_name'] ?? 'Guest'); ?></td>
                                        <td>$<?= number_format((float) $order['total'], 2); ?></td>
                                        <td><span class="admin-badge-status status-<?= htmlspecialchars($order['status']); ?>"><?= htmlspecialchars(ucfirst($order['status'])); ?></span></td>
                                        <td><?= htmlspecialchars(date('M d, Y', strtotime($order['created_at']))); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="admin-section">
                    <h2>Recent Users</h2>
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentUsers as $user): ?>
                                    <tr>
                                        <td><?= (int) $user['id']; ?></td>
                                        <td><?= htmlspecialchars($user['full_name']); ?></td>
                                        <td><?= htmlspecialchars($user['email']); ?></td>
                                        <td><?= htmlspecialchars(date('M d, Y', strtotime($user['created_at']))); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        <?php elseif ($activeTab === 'orders'): ?>
            <div class="admin-header">
                <h1>Orders</h1>
            </div>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Shipping</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allOrders as $order): ?>
                            <tr>
                                <td>#<?= (int) $order['id']; ?></td>
                                <td><?= htmlspecialchars($order['user_name'] ?? 'Guest'); ?></td>
                                <td>$<?= number_format((float) $order['total'], 2); ?></td>
                                <td><span class="admin-badge-status status-<?= htmlspecialchars($order['status']); ?>"><?= htmlspecialchars(ucfirst($order['status'])); ?></span></td>
                                <td><?= htmlspecialchars($order['shipping_mode'] ?? 'Standard'); ?></td>
                                <td><?= htmlspecialchars(date('M d, Y H:i', strtotime($order['created_at']))); ?></td>
                                <td>
                                    <form method="post" action="admin-dashboard.php?tab=orders" style="display:flex;gap:0.5rem;align-items:center;">
                                        <?= csrfField(); ?>
                                        <input type="hidden" name="order_id" value="<?= (int) $order['id']; ?>">
                                        <input type="hidden" name="status" value="<?= htmlspecialchars($order['status']); ?>" data-order-status-input="<?= (int) $order['id']; ?>">
                                        <div class="shop-select-control" data-order-status-select="<?= (int) $order['id']; ?>" style="min-width:0;padding:0 8px 0 10px;font-size:0.58rem;">
                                            <button
                                                class="shop-select-toggle"
                                                type="button"
                                                data-order-status-toggle="<?= (int) $order['id']; ?>"
                                                aria-haspopup="listbox"
                                                aria-expanded="false"
                                                aria-controls="order-status-list-<?= (int) $order['id']; ?>"
                                                style="min-width:90px;gap:6px;">
                                                <span data-order-status-current="<?= (int) $order['id']; ?>"><?= ucfirst(htmlspecialchars($order['status'])); ?></span>
                                                <i data-lucide="chevron-down" style="width:12px;height:12px;"></i>
                                            </button>
                                            <div class="shop-select-menu" id="order-status-list-<?= (int) $order['id']; ?>" role="listbox" aria-label="Select status" style="width:auto;min-width:120px;right:0;">
                                                <?php foreach (['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $s): ?>
                                                    <button
                                                        class="<?= $order['status'] === $s ? 'is-selected' : ''; ?>"
                                                        type="button"
                                                        role="option"
                                                        aria-selected="<?= $order['status'] === $s ? 'true' : 'false'; ?>"
                                                        data-order-status-option="<?= (int) $order['id']; ?>"
                                                        data-status-value="<?= htmlspecialchars($s); ?>">
                                                        <?= ucfirst($s); ?>
                                                    </button>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <button type="submit" name="update_order_status" class="admin-btn admin-btn--small">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <script>
            (function() {
                var selects = document.querySelectorAll('[data-order-status-select]');

                function updateBodyOverflow() {
                    var anyOpen = Array.from(selects).some(function(select) {
                        return select.classList.contains('is-open');
                    });
                    if (anyOpen) {
                        document.documentElement.style.overflow = 'hidden';
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.documentElement.style.overflow = '';
                        document.body.style.overflow = '';
                    }
                }

                function resetMenu(select) {
                    var menu = select.querySelector('.shop-select-menu');
                    if (menu) {
                        menu.style.position = '';
                        menu.style.top = '';
                        menu.style.left = '';
                        menu.style.right = '';
                        menu.style.width = '';
                        menu.style.minWidth = '';
                    }
                }

                function positionMenu(select) {
                    var toggle = select.querySelector('[data-order-status-toggle]');
                    var menu = select.querySelector('.shop-select-menu');
                    if (!toggle || !menu) return;
                    var rect = toggle.getBoundingClientRect();
                    menu.style.position = 'fixed';
                    menu.style.top = (rect.bottom + 6) + 'px';
                    menu.style.right = (window.innerWidth - rect.right) + 'px';
                    menu.style.left = 'auto';
                    menu.style.width = 'auto';
                    menu.style.minWidth = Math.max(rect.width, 120) + 'px';
                }

                function closeSelect(select) {
                    select.classList.remove('is-open');
                    var toggle = select.querySelector('[data-order-status-toggle]');
                    if (toggle) toggle.setAttribute('aria-expanded', 'false');
                    resetMenu(select);
                    updateBodyOverflow();
                }

                selects.forEach(function(select) {
                    var toggle = select.querySelector('[data-order-status-toggle]');
                    var options = select.querySelectorAll('[data-order-status-option]');
                    var currentLabel = select.querySelector('[data-order-status-current]');
                    var orderId = select.getAttribute('data-order-status-select');
                    var input = document.querySelector('[data-order-status-input="' + orderId + '"]');

                    if (!toggle) return;

                    toggle.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        var isOpen = select.classList.contains('is-open');
                        if (isOpen) {
                            closeSelect(select);
                        } else {
                            selects.forEach(function(other) {
                                if (other !== select) closeSelect(other);
                            });
                            positionMenu(select);
                            select.classList.add('is-open');
                            toggle.setAttribute('aria-expanded', 'true');
                            updateBodyOverflow();
                        }
                    });

                    options.forEach(function(option) {
                        option.addEventListener('click', function() {
                            var value = option.getAttribute('data-status-value');
                            var text = option.textContent.trim();

                            options.forEach(function(opt) {
                                var selected = opt === option;
                                opt.classList.toggle('is-selected', selected);
                                opt.setAttribute('aria-selected', String(selected));
                            });

                            if (currentLabel) currentLabel.textContent = text;
                            if (input) input.value = value;
                            closeSelect(select);
                        });
                    });
                });

                document.addEventListener('click', function(e) {
                    selects.forEach(function(select) {
                        if (!select.contains(e.target)) {
                            closeSelect(select);
                        }
                    });
                });

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        selects.forEach(function(select) {
                            closeSelect(select);
                        });
                    }
                });

                window.addEventListener('scroll', function() {
                    selects.forEach(function(select) {
                        if (select.classList.contains('is-open')) closeSelect(select);
                    });
                });
            })();
            </script>

        <?php elseif ($activeTab === 'products'): ?>
            <div class="admin-header">
                <h1>Products</h1>
            </div>

            <div class="admin-section" style="margin-bottom: 24px;">
                <div style="display:flex;gap:12px;">
                    <button type="button" class="admin-btn" style="flex:1;" onclick="var f=document.getElementById('add-product-form'); f.style.display=f.style.display==='none'?'block':'none';">
                        <i data-lucide="plus"></i> Add New Product
                    </button>
                    <button type="button" class="admin-btn" style="flex:1;" onclick="var m=document.getElementById('category-manager'); m.style.display=m.style.display==='none'?'block':'none';">
                        <i data-lucide="folder-open"></i> Manage Categories
                    </button>
                </div>
                <form id="add-product-form" method="post" action="admin-dashboard.php?tab=products" class="admin-form" style="display:none;margin-top:18px;">
                    <?= csrfField(); ?>
                    <div class="admin-form-grid">
                        <div class="admin-form-group">
                            <label for="prod-name">Name</label>
                            <input type="text" id="prod-name" name="name" required>
                        </div>
                        <div class="admin-form-group">
                            <label for="prod-brand">Brand</label>
                            <input type="text" id="prod-brand" name="brand" required>
                        </div>
                        <div class="admin-form-group">
                            <label for="prod-price">Price</label>
                            <input type="number" id="prod-price" name="price" step="0.01" min="0.01" required>
                        </div>
                        <div class="admin-form-group">
                            <label for="prod-category">Category</label>
                            <select id="prod-category" name="category" class="admin-select" style="width:100%;">
                                <option value="">— Select Category —</option>
                                <?php foreach ($allCategories as $cat): ?>
                                    <option value="<?= htmlspecialchars($cat['slug']); ?>"><?= htmlspecialchars($cat['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="admin-form-group">
                            <label for="prod-badge">Badge</label>
                            <input type="text" id="prod-badge" name="badge" placeholder="e.g. New, Sale">
                        </div>
                        <div class="admin-form-group">
                            <label for="prod-rating">Rating</label>
                            <input type="text" id="prod-rating" name="rating" placeholder="e.g. 4.5">
                        </div>
                        <div class="admin-form-group admin-form-group--full">
                            <label for="prod-image">Image URL</label>
                            <input type="url" id="prod-image" name="image" placeholder="https://example.com/image.png" required>
                        </div>
                        <div class="admin-form-group admin-form-group--full">
                            <label>Gallery Images</label>
                            <div id="gallery-list" class="gallery-list">
                                <div class="gallery-row">
                                    <input type="url" class="gallery-url" placeholder="https://example.com/image.png">
                                    <button type="button" class="admin-btn admin-btn--danger admin-btn--small gallery-remove">Remove</button>
                                </div>
                            </div>
                            <button type="button" class="admin-btn admin-btn--small" id="gallery-add-btn">
                                <i data-lucide="plus"></i> Add Image
                            </button>
                            <input type="hidden" name="gallery" id="gallery-combined">
                        </div>
                        <div class="admin-form-group admin-form-group--full">
                            <label for="prod-tags">Tags (comma-separated)</label>
                            <input type="text" id="prod-tags" name="tags" placeholder="e.g. sneaker, man, popular">
                        </div>
                        <div class="admin-form-group admin-form-group--full">
                            <label for="prod-description">Description</label>
                            <textarea id="prod-description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="admin-form-actions">
                        <button type="submit" name="add_product" class="admin-btn">Save Product</button>
                    </div>
                </form>

                <script>
                    (function() {
                        var form = document.getElementById('add-product-form');

                        form.addEventListener('submit', function() {
                            var inputs = form.querySelectorAll('.gallery-url');
                            var urls = [];
                            inputs.forEach(function(input) {
                                var val = input.value.trim();
                                if (val) urls.push(val);
                            });
                            document.getElementById('gallery-combined').value = urls.join('|');
                        });

                        document.getElementById('gallery-add-btn').addEventListener('click', function() {
                            var row = document.createElement('div');
                            row.className = 'gallery-row';
                            row.innerHTML = '<input type="url" class="gallery-url" placeholder="https://example.com/image.png"><button type="button" class="admin-btn admin-btn--danger admin-btn--small gallery-remove">Remove</button>';
                            document.getElementById('gallery-list').appendChild(row);
                        });

                        document.getElementById('gallery-list').addEventListener('click', function(e) {
                            if (e.target.classList.contains('gallery-remove')) {
                                e.target.parentElement.remove();
                            }
                        });
                    })();
                </script>

                <div id="category-manager" style="display:none;margin-top:18px;">
                    <div class="admin-form" style="margin-bottom:18px;">
                        <h3 style="margin:0 0 14px;font-size:0.65rem;font-weight:400;">Add New Category</h3>
                        <form method="post" action="admin-dashboard.php?tab=products">
                            <?= csrfField(); ?>
                            <div style="display:flex;gap:10px;align-items:flex-end;">
                                <div class="admin-form-group" style="flex:1;">
                                    <label for="category-name">Category Name</label>
                                    <input type="text" id="category-name" name="category_name" placeholder="e.g. Sneakers" required>
                                </div>
                                <button type="submit" name="add_category" class="admin-btn">Add</button>
                            </div>
                        </form>
                    </div>

                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($allCategories as $cat): ?>
                                    <tr>
                                        <td><?= (int) $cat['id']; ?></td>
                                        <td><?= htmlspecialchars($cat['name']); ?></td>
                                        <td><?= htmlspecialchars($cat['slug']); ?></td>
                                        <td>
                                            <form method="post" action="admin-dashboard.php?tab=products" onsubmit="return confirm('Delete this category?');" style="display:inline;">
                                                <?= csrfField(); ?>
                                                <input type="hidden" name="category_id" value="<?= (int) $cat['id']; ?>">
                                                <button type="submit" name="delete_category" class="admin-btn admin-btn--danger admin-btn--small">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <?php
            $currentCategoryLabel = 'All Categories';
            foreach ($allCategories as $cat) {
                if ($selectedCategory === $cat['slug']) {
                    $currentCategoryLabel = $cat['name'];
                    break;
                }
            }
            ?>
            <div class="admin-filter-bar" style="display:flex;justify-content:flex-end;margin-bottom:12px;">
                <form method="get" action="admin-dashboard.php" id="admin-filter-form" style="display:flex;gap:8px;align-items:center;">
                    <input type="hidden" name="tab" value="products">
                    <input type="hidden" name="category" id="admin-category-input" value="<?= htmlspecialchars($selectedCategory); ?>">
                    <input type="hidden" name="sort" id="admin-sort-input" value="<?= htmlspecialchars($selectedSort); ?>">

                    <!-- Category selector -->
                    <div class="shop-select-control" data-admin-filter-select>
                        <span class="shop-select-control__label">Category</span>
                        <button
                            class="shop-select-toggle"
                            type="button"
                            data-admin-filter-toggle
                            aria-haspopup="listbox"
                            aria-expanded="false"
                            aria-controls="admin-category-list">
                            <span data-admin-filter-current><?= htmlspecialchars($currentCategoryLabel); ?></span>
                            <i data-lucide="chevron-down"></i>
                        </button>
                        <div class="shop-select-menu" id="admin-category-list" role="listbox" aria-label="Select category">
                            <button
                                class="<?= $selectedCategory === '' ? 'is-selected' : ''; ?>"
                                type="button"
                                role="option"
                                aria-selected="<?= $selectedCategory === '' ? 'true' : 'false'; ?>"
                                data-admin-filter-option
                                data-filter-value="">
                                All Categories
                            </button>
                            <?php foreach ($allCategories as $cat): ?>
                                <button
                                    class="<?= ($selectedCategory === $cat['slug']) ? 'is-selected' : ''; ?>"
                                    type="button"
                                    role="option"
                                    aria-selected="<?= ($selectedCategory === $cat['slug']) ? 'true' : 'false'; ?>"
                                    data-admin-filter-option
                                    data-filter-value="<?= htmlspecialchars($cat['slug']); ?>">
                                    <?= htmlspecialchars($cat['name']); ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Sort selector -->
                    <div class="shop-select-control" data-admin-sort-select>
                        <span class="shop-select-control__label">Sort by</span>
                        <button
                            class="shop-select-toggle"
                            type="button"
                            data-admin-sort-toggle
                            aria-haspopup="listbox"
                            aria-expanded="false"
                            aria-controls="admin-sort-list">
                            <span data-admin-sort-current><?= htmlspecialchars($currentSortLabel); ?></span>
                            <i data-lucide="chevron-down"></i>
                        </button>
                        <div class="shop-select-menu" id="admin-sort-list" role="listbox" aria-label="Select sort">
                            <button
                                class="<?= $selectedSort === 'number_desc' ? 'is-selected' : ''; ?>"
                                type="button"
                                role="option"
                                aria-selected="<?= $selectedSort === 'number_desc' ? 'true' : 'false'; ?>"
                                data-admin-sort-option
                                data-filter-value="number_desc">
                                Number
                            </button>
                            <button
                                class="<?= $selectedSort === 'name_asc' ? 'is-selected' : ''; ?>"
                                type="button"
                                role="option"
                                aria-selected="<?= $selectedSort === 'name_asc' ? 'true' : 'false'; ?>"
                                data-admin-sort-option
                                data-filter-value="name_asc">
                                Name (A-Z)
                            </button>
                            <button
                                class="<?= $selectedSort === 'name_desc' ? 'is-selected' : ''; ?>"
                                type="button"
                                role="option"
                                aria-selected="<?= $selectedSort === 'name_desc' ? 'true' : 'false'; ?>"
                                data-admin-sort-option
                                data-filter-value="name_desc">
                                Name (Z-A)
                            </button>
                            <button
                                class="<?= $selectedSort === 'price_asc' ? 'is-selected' : ''; ?>"
                                type="button"
                                role="option"
                                aria-selected="<?= $selectedSort === 'price_asc' ? 'true' : 'false'; ?>"
                                data-admin-sort-option
                                data-filter-value="price_asc">
                                Price (Low to High)
                            </button>
                            <button
                                class="<?= $selectedSort === 'price_desc' ? 'is-selected' : ''; ?>"
                                type="button"
                                role="option"
                                aria-selected="<?= $selectedSort === 'price_desc' ? 'true' : 'false'; ?>"
                                data-admin-sort-option
                                data-filter-value="price_desc">
                                Price (High to Low)
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <script>
            (function() {
                var form = document.getElementById('admin-filter-form');

                function resetMenu(menu) {
                    menu.style.position = '';
                    menu.style.top = '';
                    menu.style.left = '';
                    menu.style.right = '';
                    menu.style.width = '';
                    menu.style.minWidth = '';
                }

                function positionMenu(toggle, menu) {
                    var rect = toggle.getBoundingClientRect();
                    menu.style.position = 'fixed';
                    menu.style.top = (rect.bottom + 6) + 'px';
                    menu.style.right = (window.innerWidth - rect.right) + 'px';
                    menu.style.left = 'auto';
                    menu.style.width = 'auto';
                    menu.style.minWidth = Math.max(rect.width, 178) + 'px';
                }

                // Category selector
                var catSelect = document.querySelector('[data-admin-filter-select]');
                var catToggle = document.querySelector('[data-admin-filter-toggle]');
                var catMenu = document.getElementById('admin-category-list');
                var catOptions = catMenu.querySelectorAll('[data-admin-filter-option]');
                var catCurrentLabel = document.querySelector('[data-admin-filter-current]');
                var catInput = document.getElementById('admin-category-input');

                function closeCatSelect() {
                    catSelect.classList.remove('is-open');
                    catToggle.setAttribute('aria-expanded', 'false');
                    resetMenu(catMenu);
                }

                catToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    var isOpen = catSelect.classList.contains('is-open');
                    if (isOpen) {
                        closeCatSelect();
                    } else {
                        closeSortSelect();
                        positionMenu(catToggle, catMenu);
                        catSelect.classList.add('is-open');
                        catToggle.setAttribute('aria-expanded', 'true');
                    }
                });

                catOptions.forEach(function(option) {
                    option.addEventListener('click', function() {
                        var value = option.getAttribute('data-filter-value');
                        var text = option.textContent.trim();

                        catOptions.forEach(function(opt) {
                            var selected = opt === option;
                            opt.classList.toggle('is-selected', selected);
                            opt.setAttribute('aria-selected', String(selected));
                        });

                        catCurrentLabel.textContent = text;
                        catInput.value = value;
                        closeCatSelect();
                        form.submit();
                    });
                });

                document.addEventListener('click', function(e) {
                    if (!catSelect.contains(e.target)) closeCatSelect();
                });

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') closeCatSelect();
                });

                // Sort selector
                var sortSelect = document.querySelector('[data-admin-sort-select]');
                var sortToggle = document.querySelector('[data-admin-sort-toggle]');
                var sortMenu = document.getElementById('admin-sort-list');
                var sortOptions = sortMenu.querySelectorAll('[data-admin-sort-option]');
                var sortCurrentLabel = document.querySelector('[data-admin-sort-current]');
                var sortInput = document.getElementById('admin-sort-input');

                function closeSortSelect() {
                    sortSelect.classList.remove('is-open');
                    sortToggle.setAttribute('aria-expanded', 'false');
                    resetMenu(sortMenu);
                }

                sortToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    var isOpen = sortSelect.classList.contains('is-open');
                    if (isOpen) {
                        closeSortSelect();
                    } else {
                        closeCatSelect();
                        positionMenu(sortToggle, sortMenu);
                        sortSelect.classList.add('is-open');
                        sortToggle.setAttribute('aria-expanded', 'true');
                    }
                });

                sortOptions.forEach(function(option) {
                    option.addEventListener('click', function() {
                        var value = option.getAttribute('data-filter-value');
                        var text = option.textContent.trim();

                        sortOptions.forEach(function(opt) {
                            var selected = opt === option;
                            opt.classList.toggle('is-selected', selected);
                            opt.setAttribute('aria-selected', String(selected));
                        });

                        sortCurrentLabel.textContent = text;
                        sortInput.value = value;
                        closeSortSelect();
                        form.submit();
                    });
                });

                document.addEventListener('click', function(e) {
                    if (!sortSelect.contains(e.target)) closeSortSelect();
                });

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') closeSortSelect();
                });

                window.addEventListener('scroll', function() {
                    closeCatSelect();
                    closeSortSelect();
                });
            })();
            </script>

            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Brand</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $productIndex = 1; foreach ($allProducts as $product): ?>
                            <tr>
                                <td><?= $productIndex++; ?></td>
                                <td><?= (int) $product['id']; ?></td>
                                <td>
                                    <img src="<?= htmlspecialchars($product['image']); ?>" alt="" class="admin-product-thumb">
                                </td>
                                <td><?= htmlspecialchars($product['name']); ?></td>
                                <td><?= htmlspecialchars($product['brand']); ?></td>
                                <td>$<?= number_format((float) $product['price'], 2); ?></td>
                                <td><?= (int) ($product['stock'] ?? 0); ?></td>
                                <td><?= htmlspecialchars($product['category_name'] ?? '—'); ?></td>
                                <td>
                                    <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                        <button type="button" class="admin-btn admin-btn--small admin-view-btn" data-admin-view-btn data-product='<?= htmlspecialchars(json_encode([
                                            'id' => (int) $product['id'],
                                            'name' => $product['name'],
                                            'brand' => $product['brand'],
                                            'description' => $product['description'] ?? '',
                                            'price' => (float) $product['price'],
                                            'tags' => $product['tags'] ?? '',
                                            'rating' => $product['rating'] ?? '',
                                            'badge' => $product['badge'] ?? '',
                                            'image' => $product['image'] ?? '',
                                            'gallery' => $product['gallery'] ?? '',
                                            'category' => $product['category_name'] ?? '—',
                                            'stock' => (int) ($product['stock'] ?? 0),
                                            'slug' => $product['slug'] ?? '',
                                            'created_at' => $product['created_at'] ?? '',
                                        ]), ENT_QUOTES, 'UTF-8'); ?>'>View</button>
                                        <button type="button" class="admin-btn admin-btn--small" data-admin-edit-btn data-edit-product='<?= htmlspecialchars(json_encode([
                                            'id' => (int) $product['id'],
                                            'name' => $product['name'],
                                            'brand' => $product['brand'],
                                            'description' => $product['description'] ?? '',
                                            'price' => (float) $product['price'],
                                            'tags' => $product['tags'] ?? '',
                                            'rating' => $product['rating'] ?? '',
                                            'badge' => $product['badge'] ?? '',
                                            'image' => $product['image'] ?? '',
                                            'gallery' => $product['gallery'] ?? '',
                                            'category' => $product['category'] ?? '',
                                            'stock' => (int) ($product['stock'] ?? 0),
                                            'slug' => $product['slug'] ?? '',
                                        ]), ENT_QUOTES, 'UTF-8'); ?>'>Update</button>
                                        <form method="post" action="admin-dashboard.php?tab=products" onsubmit="return confirm('Delete this product?');" style="display:inline;">
                                            <?= csrfField(); ?>
                                            <input type="hidden" name="product_id" value="<?= (int) $product['id']; ?>">
                                            <button type="submit" name="delete_product" class="admin-btn admin-btn--danger admin-btn--small">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($activeTab === 'users'): ?>
            <div class="admin-header">
                <h1>Users</h1>
            </div>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Gender</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $usersStmt = $pdo->query('SELECT * FROM users ORDER BY id DESC');
                        $users = $usersStmt->fetchAll();
                        foreach ($users as $user):
                        ?>
                            <tr>
                                <td><?= (int) $user['id']; ?></td>
                                <td><?= htmlspecialchars($user['full_name']); ?></td>
                                <td><?= htmlspecialchars($user['email']); ?></td>
                                <td><?= htmlspecialchars($user['phone'] ?? '—'); ?></td>
                                <td><?= htmlspecialchars(ucfirst($user['gender'] ?? '—')); ?></td>
                                <td><?= htmlspecialchars(date('M d, Y', strtotime($user['created_at']))); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($activeTab === 'requests'): ?>
            <div class="admin-header">
                <h1>User Requests</h1>
            </div>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Subject</th>
                            <th>Attached File</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allRequests as $req): ?>
                            <tr>
                                <td>#<?= (int) $req['id']; ?></td>
                                <td><?= htmlspecialchars($req['email']); ?></td>
                                <td><?= htmlspecialchars($req['user_phone'] ?? $req['phone'] ?? '—'); ?></td>
                                <td><?= htmlspecialchars($req['subject']); ?></td>
                                <td>
                                    <?php if (!empty($req['attachment'])): ?>
                                        <a href="../<?= htmlspecialchars(ltrim($req['attachment'], '/')); ?>" target="_blank" class="admin-btn admin-btn--small">View File</a>
                                    <?php else: ?>
                                        <span class="admin-badge-status status-delivered">No File</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="admin-badge-status status-<?= htmlspecialchars($req['status']); ?>"><?= htmlspecialchars(ucfirst($req['status'])); ?></span></td>
                                <td><?= htmlspecialchars(date('M d, Y H:i', strtotime($req['created_at']))); ?></td>
                                <td>
                                    <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                        <?php if ($req['status'] === 'pending'): ?>
                                            <button type="button" class="admin-btn admin-btn--small admin-view-btn" data-admin-view-req data-req='<?= htmlspecialchars(json_encode([
                                                'id' => (int) $req['id'],
                                                'email' => $req['email'],
                                                'phone' => $req['user_phone'] ?? $req['phone'] ?? '',
                                                'subject' => $req['subject'],
                                                'message' => $req['message'],
                                                'attachment' => $req['attachment'] ?? '',
                                                'status' => $req['status'],
                                                'created_at' => $req['created_at'],
                                            ]), ENT_QUOTES, 'UTF-8'); ?>'>View</button>
                                            <form method="post" action="admin-dashboard.php?tab=requests" style="display:inline;">
                                                <?= csrfField(); ?>
                                                <input type="hidden" name="request_id" value="<?= (int) $req['id']; ?>">
                                                <input type="hidden" name="status" value="accepted">
                                                <button type="submit" name="update_request_status" class="admin-btn admin-btn--small">Accept</button>
                                            </form>
                                            <form method="post" action="admin-dashboard.php?tab=requests" style="display:inline;">
                                                <?= csrfField(); ?>
                                                <input type="hidden" name="request_id" value="<?= (int) $req['id']; ?>">
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" name="update_request_status" class="admin-btn admin-btn--danger admin-btn--small">Reject</button>
                                            </form>
                                        <?php else: ?>
                                            <span class="admin-badge-status status-processed">Processed</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Request View Modal -->
            <div class="admin-product-overlay" id="admin-request-overlay" aria-hidden="true">
                <div class="admin-product-modal" role="dialog" aria-modal="true" aria-labelledby="admin-request-modal-title">
                    <button type="button" class="admin-product-modal__close" id="admin-request-modal-close" aria-label="Close">
                        <i data-lucide="x"></i>
                    </button>
                    <div class="admin-product-modal__content" id="admin-request-modal-content">
                        <!-- populated by JS -->
                    </div>
                </div>
            </div>

            <script>
            (function() {
                var overlay = document.getElementById('admin-request-overlay');
                var content = document.getElementById('admin-request-modal-content');
                var closeBtn = document.getElementById('admin-request-modal-close');
                var viewBtns = document.querySelectorAll('[data-admin-view-req]');

                function openModal(req) {
                    var html = '';
                    html += '<div class="admin-product-modal__info">';
                    html += '<h2 id="admin-request-modal-title">' + escapeHtml(req.subject) + '</h2>';
                    html += '<div class="admin-product-modal__meta">';
                    html += '<div><span>Email</span><strong>' + escapeHtml(req.email) + '</strong></div>';
                    html += '<div><span>Phone</span><strong>' + escapeHtml(req.phone || '—') + '</strong></div>';
                    html += '<div><span>Status</span><strong>' + escapeHtml(req.status.charAt(0).toUpperCase() + req.status.slice(1)) + '</strong></div>';
                    html += '</div>';
                    if (req.attachment) {
                        var attPath = req.attachment.replace(/^\//, '');
                        html += '<div style="margin-bottom:18px;"><span style="font-size:0.52rem;text-transform:uppercase;letter-spacing:0.06em;color:var(--muted);display:block;margin-bottom:4px;">Attachment</span><a href="../' + escapeHtml(attPath) + '" target="_blank" class="admin-btn admin-btn--small">View Attached File</a></div>';
                    }
                    html += '<p class="admin-product-modal__desc">' + escapeHtml(req.message) + '</p>';
                    html += '<div class="admin-product-modal__foot">';
                    if (req.created_at) {
                        html += '<span>Submitted: ' + escapeHtml(req.created_at) + '</span>';
                    }
                    html += '</div>';
                    html += '</div>';

                    content.innerHTML = html;
                    overlay.classList.add('is-open');
                    overlay.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';

                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }

                function closeModal() {
                    overlay.classList.remove('is-open');
                    overlay.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = '';
                }

                function escapeHtml(text) {
                    var div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML;
                }

                viewBtns.forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        var data = btn.getAttribute('data-req');
                        if (!data) return;
                        try {
                            var req = JSON.parse(data);
                            openModal(req);
                        } catch (e) {
                            console.error('Invalid request data', e);
                        }
                    });
                });

                closeBtn.addEventListener('click', closeModal);

                overlay.addEventListener('click', function(e) {
                    if (e.target === overlay) {
                        closeModal();
                    }
                });

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && overlay.classList.contains('is-open')) {
                        closeModal();
                    }
                });
            })();
            </script>

        <?php elseif ($activeTab === 'announcements'): ?>
            <div class="admin-header">
                <h1>Announcements</h1>
            </div>

            <?php
            $statusParam = $_GET['status'] ?? '';
            if (str_starts_with($statusParam, 'success:')):
                $sent = (int) substr($statusParam, 8);
            ?>
                <div class="admin-alert admin-alert--success">Announcement sent successfully to <?= number_format($sent); ?> user(s).</div>
            <?php elseif (str_starts_with($statusParam, 'error_upload:')): ?>
                <div class="admin-alert admin-alert--error">Upload failed: <?= htmlspecialchars(substr($statusParam, 12)); ?></div>
            <?php elseif ($statusParam === 'error_empty'): ?>
                <div class="admin-alert admin-alert--error">Subject and message are required.</div>
            <?php endif; ?>

            <div class="admin-section" style="margin-bottom: 24px;">
                <form method="post" action="admin-dashboard.php?tab=announcements" enctype="multipart/form-data" id="announcement-form">
                    <?= csrfField(); ?>
                    <div class="admin-form-grid">
                        <div class="admin-form-group admin-form-group--full">
                            <label for="ann-subject">Subject</label>
                            <input type="text" id="ann-subject" name="subject" placeholder="e.g. New Collection Launch" maxlength="120" required>
                        </div>
                        <div class="admin-form-group admin-form-group--full">
                            <label for="ann-message">Message</label>
                            <textarea id="ann-message" name="message" rows="6" placeholder="Write your announcement here..." maxlength="2000" required></textarea>
                        </div>
                        <div class="admin-form-group admin-form-group--full">
                            <label for="ann-file">Attachment (optional)</label>
                            <div class="help-request-form__file">
                                <span class="help-request-form__file-icon"><i data-lucide="upload-cloud"></i></span>
                                <span class="help-request-form__file-text" id="ann-file-text">Click to choose a file</span>
                                <span class="help-request-form__file-meta">Images & PDF only</span>
                                <input id="ann-file" name="attachment" type="file" accept="image/*,application/pdf" aria-label="Attachment">
                            </div>
                        </div>
                    </div>
                    <div class="admin-form-actions" style="margin-top: 14px;">
                        <button type="submit" name="send_announcement" class="admin-btn"><i data-lucide="send"></i> Send to All Users</button>
                    </div>
                </form>
                <script>
                (function() {
                    var fileInput = document.getElementById('ann-file');
                    var fileText = document.getElementById('ann-file-text');
                    if (fileInput && fileText) {
                        fileInput.addEventListener('change', function() {
                            fileText.textContent = fileInput.files[0]?.name || 'Choose file';
                        });
                    }
                })();
                </script>
            </div>

            <div class="admin-section">
                <h2>Sent Announcements</h2>
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Subject</th>
                                <th>Sent</th>
                                <th>Users</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $annPath = realpath(__DIR__ . '/../uploads/support/announcements.json') ?: __DIR__ . '/../uploads/support/announcements.json';
                            $announcements = [];
                            if (is_file($annPath)) {
                                $content = file_get_contents($annPath);
                                if ($content !== false) {
                                    $decoded = json_decode($content, true);
                                    if (is_array($decoded)) {
                                        $announcements = array_reverse($decoded);
                                    }
                                }
                            }
                            if (empty($announcements)):
                            ?>
                                <tr><td colspan="5" style="text-align:center;color:var(--muted);">No announcements sent yet.</td></tr>
                            <?php else:
                                foreach ($announcements as $ann):
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($ann['id'] ?? '—'); ?></td>
                                    <td><?= htmlspecialchars($ann['subject'] ?? '—'); ?></td>
                                    <td><?= (int) ($ann['sent_count'] ?? 0); ?></td>
                                    <td><?= (int) ($ann['total_users'] ?? 0); ?></td>
                                    <td><?= htmlspecialchars(date('M d, Y H:i', strtotime($ann['created_at'] ?? 'now'))); ?></td>
                                </tr>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php endif; ?>
    </main>
</div>

<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
<script>
    (function() {
        var ctx = document.getElementById('revenueChart');
        if (!ctx) return;
        var labels = [<?php foreach ($monthlyRevenue as $m): ?>'<?php echo date('M Y', strtotime($m['month'] . '-01')); ?>',<?php endforeach; ?>
        ];
        var data = [<?php foreach ($monthlyRevenue as $m): ?><?php echo (float) $m['revenue']; ?>,<?php endforeach; ?>
        ];
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Revenue ($)',
                    data: data,
                    backgroundColor: 'rgba(192, 107, 0, 0.7)',
                    borderColor: 'rgba(192, 107, 0, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                    barPercentage: 0.6,
                    categoryPercentage: 0.8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '$' + context.parsed.y.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: {
                            font: { size: 11 },
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    })();
</script>
<div class="admin-product-overlay" id="admin-product-overlay" aria-hidden="true">
    <div class="admin-product-modal" role="dialog" aria-modal="true" aria-labelledby="admin-product-modal-title">
        <button type="button" class="admin-product-modal__close" id="admin-product-modal-close" aria-label="Close">
            <i data-lucide="x"></i>
        </button>
        <div class="admin-product-modal__content" id="admin-product-modal-content">
            <!-- populated by JS -->
        </div>
    </div>
</div>

<script>
(function() {
    var overlay = document.getElementById('admin-product-overlay');
    var content = document.getElementById('admin-product-modal-content');
    var closeBtn = document.getElementById('admin-product-modal-close');
    var viewBtns = document.querySelectorAll('[data-admin-view-btn]');

    function openModal(product) {
        var gallery = [];
        if (product.gallery) {
            gallery = product.gallery.split('|').filter(function(u) { return u.trim(); });
        }
        var tags = [];
        if (product.tags) {
            tags = product.tags.split(',').map(function(t) { return t.trim(); }).filter(Boolean);
        }

        var html = '';
        html += '<div class="admin-product-modal__media">';
        html += '<img src="' + escapeHtml(product.image) + '" alt="' + escapeHtml(product.name) + '" class="admin-product-modal__hero">';
        if (gallery.length > 0) {
            html += '<div class="admin-product-modal__gallery">';
            gallery.forEach(function(url) {
                html += '<img src="' + escapeHtml(url) + '" alt="" class="admin-product-modal__thumb">';
            });
            html += '</div>';
        }
        html += '</div>';

        html += '<div class="admin-product-modal__info">';
        html += '<h2 id="admin-product-modal-title">' + escapeHtml(product.name) + '</h2>';
        if (product.badge) {
            html += '<span class="admin-product-modal__badge">' + escapeHtml(product.badge) + '</span>';
        }

        html += '<div class="admin-product-modal__meta">';
        html += '<div><span>Brand</span><strong>' + escapeHtml(product.brand) + '</strong></div>';
        html += '<div><span>Price</span><strong>$' + product.price.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</strong></div>';
        html += '<div><span>Stock</span><strong>' + product.stock + '</strong></div>';
        html += '<div><span>Category</span><strong>' + escapeHtml(product.category) + '</strong></div>';
        if (product.rating) {
            html += '<div><span>Rating</span><strong>' + escapeHtml(product.rating) + '</strong></div>';
        }
        html += '</div>';

        if (tags.length > 0) {
            html += '<div class="admin-product-modal__tags">';
            tags.forEach(function(tag) {
                html += '<span>' + escapeHtml(tag) + '</span>';
            });
            html += '</div>';
        }

        if (product.description) {
            html += '<p class="admin-product-modal__desc">' + escapeHtml(product.description) + '</p>';
        }

        html += '<div class="admin-product-modal__foot">';
        html += '<span>Slug: ' + escapeHtml(product.slug) + '</span>';
        if (product.created_at) {
            html += '<span>Created: ' + escapeHtml(product.created_at) + '</span>';
        }
        html += '</div>';
        html += '</div>';

        content.innerHTML = html;
        overlay.classList.add('is-open');
        overlay.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    function closeModal() {
        overlay.classList.remove('is-open');
        overlay.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    viewBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var data = btn.getAttribute('data-product');
            if (!data) return;
            try {
                var product = JSON.parse(data);
                openModal(product);
            } catch (e) {
                console.error('Invalid product data', e);
            }
        });
    });

    closeBtn.addEventListener('click', closeModal);

    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            closeModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && overlay.classList.contains('is-open')) {
            closeModal();
        }
    });
})();
</script>
<div class="admin-product-overlay" id="admin-edit-overlay" aria-hidden="true">
    <div class="admin-product-modal" role="dialog" aria-modal="true" aria-labelledby="admin-edit-modal-title">
        <button type="button" class="admin-product-modal__close" id="admin-edit-modal-close" aria-label="Close">
            <i data-lucide="x"></i>
        </button>
        <div id="admin-edit-modal-content">
            <h2 id="admin-edit-modal-title" style="margin:0 0 18px;color:var(--accent);font-size:clamp(1rem,1.4vw,1.3rem);font-weight:400;">Edit Product</h2>
            <form method="post" action="admin-dashboard.php?tab=products" class="admin-form" id="admin-edit-form" style="padding:0;background:transparent;border:none;">
                <input type="hidden" name="product_id" id="edit-product-id">
                <?= csrfField(); ?>
                <div class="admin-form-grid">
                    <div class="admin-form-group">
                        <label>Name</label>
                        <input type="text" name="name" id="edit-product-name" required>
                    </div>
                    <div class="admin-form-group">
                        <label>Brand</label>
                        <input type="text" name="brand" id="edit-product-brand" required>
                    </div>
                    <div class="admin-form-group">
                        <label>Price</label>
                        <input type="number" name="price" id="edit-product-price" step="0.01" min="0.01" required>
                    </div>
                    <div class="admin-form-group">
                        <label>Stock</label>
                        <input type="number" name="stock" id="edit-product-stock" min="0" required>
                    </div>
                    <div class="admin-form-group">
                        <label>Category</label>
                        <select name="category" id="edit-product-category" class="admin-select" style="width:100%;">
                            <option value="">— Select —</option>
                        </select>
                    </div>
                    <div class="admin-form-group">
                        <label>Rating</label>
                        <input type="text" name="rating" id="edit-product-rating" placeholder="e.g. 4.5">
                    </div>
                    <div class="admin-form-group">
                        <label>Badge</label>
                        <input type="text" name="badge" id="edit-product-badge" placeholder="e.g. New, Sale">
                    </div>
                    <div class="admin-form-group">
                        <label>Tags (comma-separated)</label>
                        <input type="text" name="tags" id="edit-product-tags" placeholder="e.g. sneaker, man, popular">
                    </div>
                    <div class="admin-form-group admin-form-group--full">
                        <label>Image URL</label>
                        <input type="url" name="image" id="edit-product-image" placeholder="https://example.com/image.png" required>
                    </div>
                    <div class="admin-form-group admin-form-group--full">
                        <label>Gallery Images</label>
                        <div id="edit-gallery-list" class="gallery-list">
                            <div class="gallery-row">
                                <input type="url" class="gallery-url" placeholder="https://example.com/image.png">
                                <button type="button" class="admin-btn admin-btn--danger admin-btn--small gallery-remove">Remove</button>
                            </div>
                        </div>
                        <button type="button" class="admin-btn admin-btn--small" id="edit-gallery-add-btn">
                            <i data-lucide="plus"></i> Add Image
                        </button>
                        <input type="hidden" name="gallery" id="edit-gallery-combined">
                    </div>
                    <div class="admin-form-group admin-form-group--full">
                        <label>Description</label>
                        <textarea name="description" id="edit-product-description" rows="3"></textarea>
                    </div>
                </div>
                <div class="admin-form-actions" style="margin-top:14px;">
                    <button type="submit" name="edit_product" class="admin-btn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$catJson = [];
foreach ($allCategories as $c) {
    $catJson[] = ['slug' => $c['slug'], 'name' => $c['name']];
}
?>
<script>
var adminCategories = <?= json_encode($catJson); ?>;
</script>

<script>
(function() {
    var overlay = document.getElementById('admin-edit-overlay');
    var closeBtn = document.getElementById('admin-edit-modal-close');
    var editBtns = document.querySelectorAll('[data-admin-edit-btn]');
    var form = document.getElementById('admin-edit-form');

    var galleryList = document.getElementById('edit-gallery-list');
    var galleryAddBtn = document.getElementById('edit-gallery-add-btn');
    var galleryCombined = document.getElementById('edit-gallery-combined');

    galleryAddBtn.addEventListener('click', function() {
        var row = document.createElement('div');
        row.className = 'gallery-row';
        row.innerHTML = '<input type="url" class="gallery-url" placeholder="https://example.com/image.png"><button type="button" class="admin-btn admin-btn--danger admin-btn--small gallery-remove">Remove</button>';
        galleryList.appendChild(row);
    });

    galleryList.addEventListener('click', function(e) {
        if (e.target.classList.contains('gallery-remove')) {
            e.target.parentElement.remove();
        }
    });

    form.addEventListener('submit', function() {
        var inputs = form.querySelectorAll('.gallery-url');
        var urls = [];
        inputs.forEach(function(input) {
            var val = input.value.trim();
            if (val) urls.push(val);
        });
        galleryCombined.value = urls.join('|');
    });

    function openEditModal(product) {
        document.getElementById('edit-product-id').value = product.id;
        document.getElementById('edit-product-name').value = product.name;
        document.getElementById('edit-product-brand').value = product.brand;
        document.getElementById('edit-product-price').value = product.price;
        document.getElementById('edit-product-stock').value = product.stock;
        document.getElementById('edit-product-rating').value = product.rating || '';
        document.getElementById('edit-product-badge').value = product.badge || '';
        document.getElementById('edit-product-tags').value = product.tags || '';
        document.getElementById('edit-product-image').value = product.image || '';
        document.getElementById('edit-product-description').value = product.description || '';
        document.getElementById('edit-gallery-combined').value = product.gallery || '';

        var catSelect = document.getElementById('edit-product-category');
        catSelect.innerHTML = '<option value="">— Select —</option>';
        adminCategories.forEach(function(cat) {
            var option = document.createElement('option');
            option.value = cat.slug;
            option.textContent = cat.name;
            if (cat.slug === product.category) {
                option.selected = true;
            }
            catSelect.appendChild(option);
        });

        galleryList.innerHTML = '';
        var galleryUrls = [];
        if (product.gallery) {
            galleryUrls = product.gallery.split('|').filter(function(u) { return u.trim(); });
        }
        if (galleryUrls.length === 0) {
            galleryUrls = [''];
        }
        galleryUrls.forEach(function(url) {
            var row = document.createElement('div');
            row.className = 'gallery-row';
            row.innerHTML = '<input type="url" class="gallery-url" placeholder="https://example.com/image.png" value="' + escapeHtml(url) + '"><button type="button" class="admin-btn admin-btn--danger admin-btn--small gallery-remove">Remove</button>';
            galleryList.appendChild(row);
        });

        overlay.classList.add('is-open');
        overlay.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    function closeEditModal() {
        overlay.classList.remove('is-open');
        overlay.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    editBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var data = btn.getAttribute('data-edit-product');
            if (!data) return;
            try {
                var product = JSON.parse(data);
                openEditModal(product);
            } catch (e) {
                console.error('Invalid product data', e);
            }
        });
    });

    closeBtn.addEventListener('click', closeEditModal);

    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            closeEditModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && overlay.classList.contains('is-open')) {
            closeEditModal();
        }
    });
})();
</script>
</body>
</html>
