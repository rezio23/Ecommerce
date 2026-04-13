<?php
require __DIR__ . '/data.php';
require __DIR__ . '/includes/functions.php';

if (isset($_GET['add'])) {
    $productId = (int) $_GET['add'];
    $product = getProductById($products, $productId);

    if ($product !== null) {
        addToCart($productId, 1);
    }

    header('Location: index.php?added=1#products');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($site['brand']) ?> | PHP E-Commerce</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php require __DIR__ . '/includes/header.php'; ?>

    <?php if (isset($_GET['added'])): ?>
        <div class="notice-wrap">
            <div class="container notice success">Product added to cart successfully.</div>
        </div>
    <?php endif; ?>

    <main>
        <section class="hero section-space">
            <div class="container hero-grid">
                <div class="hero-content">
                    <span class="eyebrow"><?= htmlspecialchars($hero['eyebrow']) ?></span>
                    <h1><?= htmlspecialchars($hero['title']) ?></h1>
                    <p><?= htmlspecialchars($hero['subtitle']) ?></p>
                    <a href="<?= htmlspecialchars($hero['button_link']) ?>" class="primary-btn">
                        <?= htmlspecialchars($hero['button_text']) ?>
                    </a>
                </div>

                <div class="hero-image-wrap">
                    <img src="<?= htmlspecialchars($hero['image']) ?>" alt="<?= htmlspecialchars($hero['image_alt']) ?>" class="hero-image">
                </div>
            </div>
        </section>

        <section class="products-section section-space" id="products">
            <div class="container">
                <div class="section-header left-align">
                    <span>Our Products</span>
                    <h2>Click any product to open the single product page</h2>
                    <p>The updated version follows the assignment idea: product cards open a detail page with a larger image, thumbnails, price, quantity, and product information.</p>
                </div>

                <div class="product-grid">
                    <?php foreach ($products as $product): ?>
                        <?= renderProductCard($product) ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
