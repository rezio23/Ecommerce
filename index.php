<?php
$navItems = [
    ['label' => 'Home', 'href' => '#home', 'active' => true],
    ['label' => 'Gender', 'href' => '#gender'],
    ['label' => 'Shop', 'href' => '#shop'],
    ['label' => 'About', 'href' => '#about'],
    ['label' => 'New', 'href' => '#new'],
];

$brandBadges = [
    ['name' => 'adidas', 'abbr' => 'ADI'],
    ['name' => 'nike', 'abbr' => 'NIKE'],
    ['name' => 'polo', 'abbr' => 'POLO'],
];

$tickerBrands = ['POLO', 'BALENCIAGA', 'adidas', 'NIKE', 'PUMA', 'GUCCI', 'POLO', 'VERSACE'];

$brandRanking = [
    [
        'name' => 'Balenciaga',
        'count' => 48,
        'image' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=560&q=85',
        'alt' => 'Balenciaga inspired black streetwear look',
    ],
    [
        'name' => 'Gucci',
        'count' => 12,
        'image' => 'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?auto=format&fit=crop&w=560&q=85',
        'alt' => 'Gucci inspired premium handbag styling',
    ],
    [
        'name' => 'Nike',
        'count' => 48,
        'image' => 'https://images.unsplash.com/photo-1543076447-215ad9ba6923?auto=format&fit=crop&w=560&q=85',
        'alt' => 'Nike black puffer jacket outfit',
        'active' => true,
    ],
    [
        'name' => 'Polo',
        'count' => 32,
        'image' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=560&q=85',
        'alt' => 'Polo inspired fragrance bottle',
    ],
    [
        'name' => 'Adidas',
        'count' => 48,
        'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=560&q=85',
        'alt' => 'Adidas inspired sneaker in rose color',
    ],
];

$activeBrandIndex = array_search(true, array_column($brandRanking, 'active'), true);
$activeBrandIndex = $activeBrandIndex === false ? 0 : $activeBrandIndex;

$categoryCards = [
    [
        'label' => 'Perfume',
        'image' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=520&q=80',
        'class' => 'category-card--tall',
    ],
    [
        'label' => 'Clothes',
        'image' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=520&q=80',
        'class' => 'category-card--high',
    ],
    [
        'label' => 'Bag',
        'image' => 'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?auto=format&fit=crop&w=520&q=80',
        'class' => 'category-card--mid',
    ],
    [
        'label' => 'Accessories',
        'image' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?auto=format&fit=crop&w=520&q=80',
        'class' => 'category-card--wide',
    ],
    [
        'label' => 'Premium',
        'image' => 'https://images.unsplash.com/photo-1523293182086-7651a899d37f?auto=format&fit=crop&w=520&q=80',
        'class' => 'category-card--high',
    ],
];

$products = [
    [
        'name' => 'Midnight Puffer',
        'brand' => 'Nike',
        'price' => 189,
        'image' => 'https://images.unsplash.com/photo-1543076447-215ad9ba6923?auto=format&fit=crop&w=620&q=80',
    ],
    [
        'name' => 'Orange Stitch Tote',
        'brand' => 'Gucci',
        'price' => 246,
        'image' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=620&q=80',
    ],
    [
        'name' => 'Polo Night Pour',
        'brand' => 'Polo',
        'price' => 96,
        'image' => 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?auto=format&fit=crop&w=620&q=80',
    ],
    [
        'name' => 'Runner Rose',
        'brand' => 'Adidas',
        'price' => 132,
        'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=620&q=80',
    ],
];

$featureLine = ['PREMIUM FABRIC', 'MODERN LIFESTYLE', 'FABRIC QUALITY', 'TIMELESS CUTS', 'CLASSIC AND COMFORT'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The DS | Luxury Ecommerce</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Doto:wght@400;600;700;800&family=Krona+One&family=Modak&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css?v=5">
</head>
<body>
    <header class="site-header" id="home">
        <a class="brand-mark" href="#home" aria-label="The DS home">the DS</a>

        <nav class="site-nav" aria-label="Primary navigation">
            <?php foreach ($navItems as $item): ?>
                <a class="<?= !empty($item['active']) ? 'is-active' : ''; ?>" href="<?= htmlspecialchars($item['href']); ?>">
                    <?= htmlspecialchars($item['label']); ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="header-actions" aria-label="Store actions">
            <button class="icon-button search-trigger" type="button" aria-label="Search products" title="Search">
                <i data-lucide="search"></i>
            </button>
            <button class="icon-button bag-button" type="button" aria-label="Shopping bag" title="Bag">
                <i data-lucide="shopping-bag"></i>
                <span class="bag-count" aria-live="polite">0</span>
            </button>
            <button class="icon-button" type="button" aria-label="Account" title="Account">
                <i data-lucide="user-round"></i>
            </button>
        </div>
    </header>

    <main>
        <section class="hero-section" aria-labelledby="hero-heading">
            <div class="hero-copy">
                <p class="pixel-note">/New Arrival<br>Collection 2026</p>
                <h1 id="hero-heading">Stylish your <span>- Fashion</span></h1>

                <div class="brand-badges" aria-label="Featured brands">
                    <?php foreach ($brandBadges as $badge): ?>
                        <span class="brand-badge" title="<?= htmlspecialchars($badge['name']); ?>">
                            <?= htmlspecialchars($badge['abbr']); ?>
                        </span>
                    <?php endforeach; ?>
                    <a class="add-orbit" href="#shop" aria-label="Explore more brands">
                        <i data-lucide="plus"></i>
                    </a>
                </div>
            </div>

            <figure class="hero-model">
                <img src="https://images.unsplash.com/photo-1516826957135-700dedea698c?auto=format&fit=crop&w=850&q=85" alt="Model wearing a black luxury outfit">
            </figure>

            <div class="hero-sidecopy">
                <p>Explore many types<br>of BRAND with the best<br>stylize design</p>
                <h2>Every<br><span>where -</span></h2>
            </div>
        </section>

        <section class="brand-ticker" aria-label="Luxury brands">
            <div class="brand-track">
                <?php for ($i = 0; $i < 2; $i++): ?>
                    <?php foreach ($tickerBrands as $brand): ?>
                        <span><?= htmlspecialchars($brand); ?></span>
                    <?php endforeach; ?>
                <?php endfor; ?>
            </div>
        </section>

        <section class="moment-section" id="about" aria-labelledby="moment-heading">
            <div class="moment-media">
                <h2 id="moment-heading">All about -<br><span>2026</span> moment</h2>
                <div class="moment-stack">
                    <img class="stack-img stack-img--left" src="https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=500&q=80" alt="">
                    <img class="stack-img stack-img--center" data-brand-image src="https://images.unsplash.com/photo-1543076447-215ad9ba6923?auto=format&fit=crop&w=560&q=85" alt="Nike black puffer jacket outfit">
                    <img class="stack-img stack-img--right" src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=500&q=80" alt="">
                </div>
                <a class="outline-cta" href="#new">
                    See Product
                    <i data-lucide="arrow-right"></i>
                </a>
            </div>

            <div class="moment-copy">
                <ul class="brand-ranking" aria-label="Brand inventory counts">
                    <?php foreach ($brandRanking as $index => $brand): ?>
                        <li class="<?= !empty($brand['active']) ? 'is-active' : ''; ?>" data-brand-item data-brand-index="<?= (int) $index; ?>" data-slot="<?= (int) ($index - $activeBrandIndex); ?>">
                            <button
                                type="button"
                                data-brand-trigger
                                data-brand-name="<?= htmlspecialchars($brand['name']); ?>"
                                data-brand-image="<?= htmlspecialchars($brand['image']); ?>"
                                data-brand-alt="<?= htmlspecialchars($brand['alt']); ?>"
                                aria-pressed="<?= !empty($brand['active']) ? 'true' : 'false'; ?>"
                            >
                                <span class="brand-label">
                                    <span class="brand-label__short"><?= htmlspecialchars(substr($brand['name'], 0, 3)); ?><?= strlen($brand['name']) > 3 ? '...' : ''; ?></span>
                                    <span class="brand-label__full"><?= htmlspecialchars($brand['name']); ?></span>
                                </span>
                                <span>(<?= (int) $brand['count']; ?>)</span>
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <p class="pixel-quote">Everything is absolutely perfect!<br>From the fabric quality to the flawless fit.</p>
            </div>
        </section>

        <section class="feature-ribbon" aria-label="Store quality highlights">
            <?php foreach ($featureLine as $feature): ?>
                <span><?= htmlspecialchars($feature); ?></span>
            <?php endforeach; ?>
        </section>

        <section class="category-section" id="shop" aria-labelledby="category-heading">
            <div class="section-heading">
                <h2 id="category-heading">Explore with all<br><span>- luxuries</span></h2>
                <a href="#new" class="text-link">2026</a>
            </div>

            <div class="category-board" id="gender">
                <?php foreach ($categoryCards as $card): ?>
                    <article class="category-card <?= htmlspecialchars($card['class']); ?>">
                        <h3><?= htmlspecialchars($card['label']); ?></h3>
                        <a href="#new" aria-label="Shop <?= htmlspecialchars($card['label']); ?>">
                            <img src="<?= htmlspecialchars($card['image']); ?>" alt="<?= htmlspecialchars($card['label']); ?> fashion category">
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="products-section" id="new" aria-labelledby="products-heading">
            <div class="section-heading section-heading--products">
                <h2 id="products-heading">New drops<br><span>- ready now</span></h2>
                <p class="pixel-note">Curated premium pieces<br>for daily movement.</p>
            </div>

            <div class="search-panel" hidden>
                <label for="product-search">Search collection</label>
                <input id="product-search" type="search" placeholder="Try Nike, bag, puffer...">
            </div>

            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <article class="product-card" data-product-card data-name="<?= htmlspecialchars(strtolower($product['name'] . ' ' . $product['brand'])); ?>">
                        <a class="product-image" href="#" aria-label="View <?= htmlspecialchars($product['name']); ?>">
                            <img src="<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                        </a>
                        <div class="product-info">
                            <p><?= htmlspecialchars($product['brand']); ?></p>
                            <h3><?= htmlspecialchars($product['name']); ?></h3>
                            <span>$<?= number_format($product['price']); ?></span>
                        </div>
                        <button class="cart-button" type="button" data-add-to-cart>
                            <span>Add</span>
                            <i data-lucide="shopping-bag"></i>
                        </button>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <a class="brand-mark brand-mark--footer" href="#home">the DS</a>
        <p><span>- Fabric Luxury</span><br>and Premium</p>
    </footer>

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="assets/js/app.js?v=3"></script>
</body>
</html>
