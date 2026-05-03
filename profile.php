<?php
$navItems = [
    ['label' => 'Home', 'href' => 'index.php#home'],
    ['label' => 'Gender', 'href' => 'index.php#gender'],
    ['label' => 'Shop', 'href' => 'shop.php', 'active' => true],
    ['label' => 'About', 'href' => 'index.php#about'],
    ['label' => 'New', 'href' => 'index.php#new'],
];

$profileUser = [
    'name' => 'Vichhean Sombath',
    'handle' => '@sombath123',
    'email' => 'sombath@gmail.com',
    'phone' => 'Unknown',
    'gender' => 'Hidden',
    'location' => 'Sen Sok, Phnom Penh, Cambodia',
    'avatar' => 'https://i1.sndcdn.com/avatars-tDQKBExQks6cE0zh-HO3N7Q-t240x240.jpg',
];

$profileProducts = [
    [
        'name' => 'Paradigme Eau de Parfum',
        'brand' => 'Prada',
        'description' => 'Ambery woody fragrance in a refillable bottle.',
        'price' => 19.99,
        'image' => 'https://cosmeticsbusiness.com/article-image-alias/spider-man-s-tom-holland-swings-into-prada.jpg',
        'href' => 'product-detail.php?product=paradigme-eau-de-parfum',
    ],
    [
        'name' => 'Paradigme Eau de Parfum',
        'brand' => 'Prada',
        'description' => 'Ambery woody fragrance in a refillable bottle.',
        'price' => 19.99,
        'image' => 'https://perfumeuae.com/wp-content/uploads/2025/08/para-1.jpg',
        'href' => 'product-detail.php?product=paradigme-eau-de-parfum',
    ],
    [
        'name' => 'Paradigme Eau de Parfum',
        'brand' => 'Prada',
        'description' => 'Ambery woody fragrance in a refillable bottle.',
        'price' => 19.99,
        'image' => 'https://tb-static.uber.com/prod/image-proc/processed_images/f2ee7468b6c1bf9e73326764b691e585/b4665c191b34baf3d0e0fa45dfdd3d1d.jpeg',
        'href' => 'product-detail.php?product=paradigme-eau-de-parfum',
    ],
    [
        'name' => 'Paradigme Eau de Parfum',
        'brand' => 'Prada',
        'description' => 'Ambery woody fragrance in a refillable bottle.',
        'price' => 19.99,
        'image' => 'https://profumerialanza.com/cdn/shop/files/prada_paradigme_eau_de_parfum_img1.jpg?v=1769518444&width=900',
        'href' => 'product-detail.php?product=paradigme-eau-de-parfum',
    ],
    [
        'name' => 'Paradigme Eau de Parfum',
        'brand' => 'Prada',
        'description' => 'Ambery woody fragrance in a refillable bottle.',
        'price' => 19.99,
        'image' => 'https://www.prada-beauty.com/on/demandware.static/-/Sites-prada-us-Library/default/dw3cab6365/images/plp/pushes/nav-flyout/NAV-FRAG-PARADIGME.jpg',
        'href' => 'product-detail.php?product=paradigme-eau-de-parfum',
    ],
    [
        'name' => 'Paradigme Eau de Parfum',
        'brand' => 'Prada',
        'description' => 'Ambery woody fragrance in a refillable bottle.',
        'price' => 19.99,
        'image' => 'https://cosmeticsbusiness.com/article-image-alias/spider-man-s-tom-holland-swings-into-prada.jpg',
        'href' => 'product-detail.php?product=paradigme-eau-de-parfum',
    ],
];

$orderProducts = $profileProducts;
$favoriteProducts = array_reverse($profileProducts);

function renderProfileProductCard(array $product): void
{
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
    <link rel="stylesheet" href="assets/css/styles.css?v=92">
</head>
<body class="profile-page">
    <header class="site-header" id="profile-top">
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
                <input class="header-search-input" id="header-profile-search" data-product-search type="search" placeholder="Search products..." aria-label="Search products" autocomplete="off" tabindex="-1" aria-hidden="true">
                <button class="icon-button search-trigger" type="button" aria-label="Open search" aria-controls="header-profile-search" aria-expanded="false" title="Search">
                    <i data-lucide="search"></i>
                </button>
            </div>
            <button class="icon-button bag-button" type="button" aria-label="Shopping bag" title="Bag">
                <i data-lucide="shopping-bag"></i>
                <span class="bag-count" aria-live="polite">0</span>
            </button>
            <a class="icon-button is-active" href="profile.php" aria-label="Account profile" title="Account">
                <i data-lucide="user-round"></i>
            </a>
        </div>
    </header>

    <main class="profile-main">
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
                <a href="#">Term Condition</a>
                <span aria-hidden="true"></span>
                <a href="#">Logout</a>
            </nav>
        </aside>

        <div class="profile-content">
            <?php renderProfileProductSection('Your Order', $orderProducts, 'profile-orders-title'); ?>
            <?php renderProfileProductSection('Your Favorite', $favoriteProducts, 'profile-favorites-title'); ?>
        </div>
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

    <div class="edit-profile-overlay" id="edit-profile-modal" aria-hidden="true">
        <div class="edit-profile-modal" role="dialog" aria-modal="true" aria-labelledby="edit-profile-title">
            <button type="button" class="edit-profile-close" data-edit-close aria-label="Close edit profile">
                <i data-lucide="x"></i>
            </button>
            <h2 id="edit-profile-title">Edit Personal Detail</h2>
            <hr class="edit-form-divider">
            <form class="edit-form" action="profile.php" method="post">
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
    <script src="assets/js/app.js?v=23"></script>
</body>
</html>
