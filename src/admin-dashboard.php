<?php
require 'includes/security.php';
require 'includes/db.php';

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

// All products for management
$productsStmt = $pdo->query('SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category = c.slug ORDER BY p.id DESC');
$allProducts = $productsStmt->fetchAll();

// All orders for management
$allOrdersStmt = $pdo->query('SELECT o.*, u.full_name AS user_name FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC');
$allOrders = $allOrdersStmt->fetchAll();

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
    <link rel="stylesheet" href="../assets/css/styles.css?v=102">
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
        </nav>
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
                                        <select name="status" class="admin-select">
                                            <?php foreach (['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $s): ?>
                                                <option value="<?= $s; ?>" <?= $order['status'] === $s ? 'selected' : ''; ?>><?= ucfirst($s); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" name="update_order_status" class="admin-btn admin-btn--small">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($activeTab === 'products'): ?>
            <div class="admin-header">
                <h1>Products</h1>
            </div>

            <div class="admin-section" style="margin-bottom: 24px;">
                <button type="button" class="admin-btn" onclick="document.getElementById('add-product-form').classList.toggle('is-open');">
                    <i data-lucide="plus"></i> Add New Product
                </button>
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
            </div>

            <script>
                (function() {
                    var form = document.getElementById('add-product-form');
                    var btn = form.previousElementSibling;
                    btn.addEventListener('click', function() {
                        form.style.display = form.style.display === 'none' ? 'block' : 'none';
                    });

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

            <div class="admin-section" style="margin-bottom: 24px;">
                <button type="button" class="admin-btn" onclick="document.getElementById('category-manager').classList.toggle('is-open');">
                    <i data-lucide="folder-open"></i> Manage Categories
                </button>
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

            <script>
                (function() {
                    var manager = document.getElementById('category-manager');
                    var btn = manager.previousElementSibling;
                    btn.addEventListener('click', function() {
                        manager.style.display = manager.style.display === 'none' ? 'block' : 'none';
                    });
                })();
            </script>

            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Brand</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allProducts as $product): ?>
                            <tr>
                                <td><?= (int) $product['id']; ?></td>
                                <td>
                                    <img src="<?= htmlspecialchars($product['image']); ?>" alt="" class="admin-product-thumb">
                                </td>
                                <td><?= htmlspecialchars($product['name']); ?></td>
                                <td><?= htmlspecialchars($product['brand']); ?></td>
                                <td>$<?= number_format((float) $product['price'], 2); ?></td>
                                <td><?= htmlspecialchars($product['category_name'] ?? '—'); ?></td>
                                <td>
                                    <form method="post" action="admin-dashboard.php?tab=products" onsubmit="return confirm('Delete this product?');" style="display:inline;">
                                        <?= csrfField(); ?>
                                        <input type="hidden" name="product_id" value="<?= (int) $product['id']; ?>">
                                        <button type="submit" name="delete_product" class="admin-btn admin-btn--danger admin-btn--small">Delete</button>
                                    </form>
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
</body>
</html>
