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

// Recent orders
$recentOrdersStmt = $pdo->query('SELECT o.*, u.full_name AS user_name FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 10');
$recentOrders = $recentOrdersStmt->fetchAll();

// Recent users
$recentUsersStmt = $pdo->query('SELECT id, full_name, email, created_at FROM users ORDER BY created_at DESC LIMIT 10');
$recentUsers = $recentUsersStmt->fetchAll();

// All products for management
$productsStmt = $pdo->query('SELECT * FROM products ORDER BY id DESC');
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
    <link rel="stylesheet" href="../assets/css/styles.css?v=96">
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
                                <td><?= htmlspecialchars($product['category'] ?? '—'); ?></td>
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
</body>
</html>
