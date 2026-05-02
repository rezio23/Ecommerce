<?php
$navItems = [
    ['label' => 'Home', 'href' => 'index.php#home'],
    ['label' => 'Gender', 'href' => 'index.php#gender'],
    ['label' => 'Shop', 'href' => 'shop.php', 'active' => true],
    ['label' => 'About', 'href' => 'index.php#about'],
    ['label' => 'New', 'href' => 'index.php#new'],
];

$product = [
    'name' => 'Paradigme Eau de Parfum',
    'brand' => 'Prada',
    'description' => 'Ambery woody fragrance in a refillable bottle.',
    'category' => 'Men\'s Perfume',
    'price' => 165,
    'rating' => '4.8',
    'badge' => 'New Arrival',
    'sizes' => ['30ML', '50ML', '90ML', '100ML', '150ML', 'Refill'],
    'active_size' => '100ML',
];

$productGallery = [
    [
        'image' => 'https://perfumeuae.com/wp-content/uploads/2025/08/para-1.jpg',
        'alt' => 'Prada Paradigme perfume campaign with green bottle',
    ],
    [
        'image' => 'https://tb-static.uber.com/prod/image-proc/processed_images/f2ee7468b6c1bf9e73326764b691e585/b4665c191b34baf3d0e0fa45dfdd3d1d.jpeg',
        'alt' => 'Prada Paradigme Eau de Parfum second gallery image',
    ],
    [
        'image' => 'https://profumerialanza.com/cdn/shop/files/prada_paradigme_eau_de_parfum_img1.jpg?v=1769518444&width=900',
        'alt' => 'Prada Paradigme Eau de Parfum bottle product image',
    ],
    [
        'image' => 'https://www.prada-beauty.com/on/demandware.static/-/Sites-prada-us-Library/default/dw3cab6365/images/plp/pushes/nav-flyout/NAV-FRAG-PARADIGME.jpg',
        'alt' => 'Prada Paradigme fragrance bottle on green background',
    ],
];

$similarProducts = [
    [
        'name' => 'Blue Signature Perfume',
        'image' => 'https://foryou.ma/cdn/shop/files/photo_5922755336093764687_y.jpg?v=1766189500',
    ],
    [
        'name' => 'Polo Blue Parfum',
        'image' => 'https://www.fragrancedirect.co.uk/images?url=https://static.thcdn.com/productimg/original/13522064-1065266787614683.jpg&format=webp&auto=avif&width=1000&height=1000&fit=cover',
    ],
    [
        'name' => 'Prada Luna Rossa',
        'image' => 'https://media.ulta.com/i/ulta/77010863_alt01?w=800&h=800&fmt=auto',
    ],
    [
        'name' => 'Amber Signature',
        'image' => 'https://i.makeup.be/g/go/goppxwiupxl3.jpg',
    ],
    [
        'name' => 'Designer Fragrance',
        'image' => 'https://www.allbeauty.com/images?url=https://static.thcdn.com/productimg/original/12271391-1835265763900530.jpg&format=webp&auto=avif&width=1000&height=1000&fit=cover',
    ],
];

$activeGallery = $productGallery[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']); ?> | The DS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Doto:wght@400;600;700;800&family=Krona+One&family=Modak&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css?v=82">
</head>
<body class="product-detail-page">
    <header class="site-header" id="product-top">
        <a class="brand-mark" href="index.php#home" aria-label="The DS home">the DS</a>

        <nav class="site-nav" aria-label="Primary navigation">
            <?php foreach ($navItems as $item): ?>
                <a class="<?= !empty($item['active']) ? 'is-active' : ''; ?>" href="<?= htmlspecialchars($item['href']); ?>">
                    <?= htmlspecialchars($item['label']); ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="header-actions" aria-label="Store actions">
            <div class="header-search" data-header-search>
                <input class="header-search-input" id="header-product-search" data-product-search type="search" placeholder="Search products..." aria-label="Search products" autocomplete="off" tabindex="-1" aria-hidden="true">
                <button class="icon-button search-trigger" type="button" aria-label="Open search" aria-controls="header-product-search" aria-expanded="false" title="Search">
                    <i data-lucide="search"></i>
                </button>
            </div>
            <button class="icon-button bag-button" type="button" aria-label="Shopping bag" title="Bag">
                <i data-lucide="shopping-bag"></i>
                <span class="bag-count" aria-live="polite">0</span>
            </button>
            <button class="icon-button" type="button" aria-label="Account" title="Account">
                <i data-lucide="user-round"></i>
            </button>
        </div>
    </header>

    <main class="product-detail-main">
        <nav class="product-breadcrumb" aria-label="Breadcrumb">
            <a href="shop.php">Shop</a>
            <span>/</span>
            <a href="shop.php#shop-grid">Man</a>
            <span>/</span>
            <span><?= htmlspecialchars($product['name']); ?></span>
        </nav>

        <section class="product-detail-layout" aria-labelledby="product-title">
            <div class="product-detail-media">
                <div class="product-gallery" data-product-gallery>
                    <div class="product-thumbs" role="list" aria-label="Product images">
                        <?php foreach ($productGallery as $index => $galleryItem): ?>
                            <button
                                class="product-thumb <?= $index === 0 ? 'is-active' : ''; ?>"
                                type="button"
                                role="listitem"
                                data-product-gallery-thumb
                                data-gallery-image="<?= htmlspecialchars($galleryItem['image']); ?>"
                                data-gallery-alt="<?= htmlspecialchars($galleryItem['alt']); ?>"
                                aria-label="Show image <?= $index + 1; ?>"
                                aria-selected="<?= $index === 0 ? 'true' : 'false'; ?>"
                            >
                                <img src="<?= htmlspecialchars($galleryItem['image']); ?>" alt="">
                            </button>
                        <?php endforeach; ?>
                    </div>

                    <figure class="product-hero-media">
                        <img data-product-gallery-main src="<?= htmlspecialchars($activeGallery['image']); ?>" alt="<?= htmlspecialchars($activeGallery['alt']); ?>">
                        <figcaption class="product-badges" aria-label="Product badges">
                            <span><?= htmlspecialchars($product['badge']); ?></span>
                            <span><?= htmlspecialchars($product['rating']); ?> star</span>
                        </figcaption>
                    </figure>
                </div>

                <div class="product-detail-actions">
                    <button class="product-primary-button" type="button" data-add-to-cart>
                        <span>Add to Cart</span>
                    </button>
                    <button class="product-secondary-button" type="button">Favorite</button>
                </div>
            </div>

            <article class="product-detail-info">
                <p class="product-detail-brand"><?= htmlspecialchars($product['brand']); ?></p>
                <h1 id="product-title"><?= htmlspecialchars($product['name']); ?></h1>
                <p class="product-detail-description"><?= htmlspecialchars($product['description']); ?></p>
                <p class="product-detail-category"><?= htmlspecialchars($product['category']); ?></p>
                <p class="product-detail-price">$ <?= number_format($product['price'], 2); ?></p>

                <section class="product-option-group" aria-labelledby="product-size-title">
                    <h2 id="product-size-title">Size</h2>
                    <div class="product-size-grid" data-product-size-group>
                        <?php foreach ($product['sizes'] as $size): ?>
                            <button
                                class="<?= $size === $product['active_size'] ? 'is-active' : ''; ?>"
                                type="button"
                                data-product-size-option
                                data-size-value="<?= htmlspecialchars($size); ?>"
                                aria-pressed="<?= $size === $product['active_size'] ? 'true' : 'false'; ?>"
                            >
                                <?= htmlspecialchars($size); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </section>

                <section class="product-option-group product-similar-group" aria-labelledby="similar-product-title">
                    <h2 id="similar-product-title">Similar Product</h2>
                    <div class="product-similar-list">
                        <?php foreach ($similarProducts as $index => $similarProduct): ?>
                            <button
                                type="button"
                                data-product-gallery-thumb
                                data-gallery-image="<?= htmlspecialchars($similarProduct['image']); ?>"
                                data-gallery-alt="<?= htmlspecialchars($similarProduct['name']); ?>"
                                aria-label="Show <?= htmlspecialchars($similarProduct['name']); ?>"
                                aria-selected="false"
                            >
                                <img src="<?= htmlspecialchars($similarProduct['image']); ?>" alt="<?= htmlspecialchars($similarProduct['name']); ?>">
                            </button>
                        <?php endforeach; ?>
                    </div>
                </section>
            </article>
        </section>
    </main>

    <footer class="site-footer">
        <section class="footer-brand" aria-label="Store footer">
            <a class="brand-mark brand-mark--footer" href="index.php#home">the DS</a>
            <p><span>- Fabric Luxury</span><br>and Premium</p>

            <div class="footer-socials" aria-label="Social links">
                <a class="footer-social" href="#" aria-label="Facebook" title="Facebook">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/e/ee/Logo_de_Facebook.png" alt="">
                </a>
                <a class="footer-social" href="#" aria-label="Telegram" title="Telegram">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/82/Telegram_logo.svg/960px-Telegram_logo.svg.png" alt="">
                </a>
                <a class="footer-social" href="#" aria-label="Instagram" title="Instagram">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e7/Instagram_logo_2016.svg/3840px-Instagram_logo_2016.svg.png" alt="">
                </a>
                <a class="footer-social" href="#" aria-label="TikTok" title="TikTok">
                    <img src="https://img.freepik.com/premium-vector/tik-tok-logo_578229-290.jpg?semt=ais_hybrid&amp;w=740&amp;q=80" alt="">
                </a>
            </div>
        </section>

        <div class="footer-groups">
            <section class="footer-group">
                <h2>Location</h2>
                <p>Phnom Penh, Cambodia</p>
            </section>
            <section class="footer-group">
                <h2>Call Us</h2>
                <p><a href="tel:+855112233">+855 112 233</a></p>
            </section>
            <section class="footer-group">
                <h2>Email</h2>
                <p><a href="mailto:thedaservice@store.com">thedaservice@store.com</a></p>
            </section>

            <nav class="footer-group footer-links" aria-label="Footer home links">
                <h2>Home</h2>
                <a href="index.php#about">About</a>
                <a href="index.php#new">Products</a>
                <a href="index.php#gender">Categories</a>
            </nav>
            <nav class="footer-group footer-links" aria-label="Footer shop links">
                <h2>Shop</h2>
                <a href="shop.php#shop-grid">Clothes</a>
                <a href="shop.php#shop-grid">Perfumes</a>
                <a href="shop.php#shop-grid">Accessories</a>
            </nav>
            <nav class="footer-group footer-links footer-links--brands" aria-label="Footer brand links">
                <h2>Brand</h2>
                <div>
                    <a href="shop.php#shop-grid">Polo</a>
                    <a href="shop.php#shop-grid">Balenciaga</a>
                    <a href="shop.php#shop-grid">Prada</a>
                    <a href="shop.php#shop-grid">Puma</a>
                    <a href="shop.php#shop-grid">Gucci</a>
                    <a href="shop.php#shop-grid">Nike</a>
                </div>
            </nav>
        </div>
    </footer>

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="assets/js/app.js?v=20"></script>
</body>
</html>
