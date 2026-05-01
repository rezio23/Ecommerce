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
        'stack' => [
            'left' => [
                'image' => 'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=500&q=80',
                'alt' => 'Layered streetwear outfit detail',
            ],
            'center' => [
                'image' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=560&q=85',
                'alt' => 'Balenciaga inspired black streetwear look',
            ],
            'right' => [
                'image' => 'https://images.unsplash.com/photo-1523293182086-7651a899d37f?auto=format&fit=crop&w=500&q=80',
                'alt' => 'Premium folded fashion pieces',
            ],
        ],
    ],
    [
        'name' => 'Gucci',
        'count' => 12,
        'stack' => [
            'left' => [
                'image' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?auto=format&fit=crop&w=500&q=80',
                'alt' => 'Luxury sunglasses accessory detail',
            ],
            'center' => [
                'image' => 'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?auto=format&fit=crop&w=560&q=85',
                'alt' => 'Gucci inspired premium handbag styling',
            ],
            'right' => [
                'image' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=500&q=80',
                'alt' => 'Structured designer handbag',
            ],
        ],
    ],
    [
        'name' => 'Nike',
        'count' => 48,
        'stack' => [
            'left' => [
                'image' => 'https://images.unsplash.com/photo-1516826957135-700dedea698c?auto=format&fit=crop&w=500&q=80',
                'alt' => 'Athletic black outfit styling',
            ],
            'center' => [
                'image' => 'https://images.unsplash.com/photo-1543076447-215ad9ba6923?auto=format&fit=crop&w=560&q=85',
                'alt' => 'Nike black puffer jacket outfit',
            ],
            'right' => [
                'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=500&q=80',
                'alt' => 'Sport sneaker product closeup',
            ],
        ],
        'active' => true,
    ],
    [
        'name' => 'Polo',
        'count' => 32,
        'stack' => [
            'left' => [
                'image' => 'https://images.unsplash.com/photo-1523293182086-7651a899d37f?auto=format&fit=crop&w=500&q=80',
                'alt' => 'Classic folded wardrobe essentials',
            ],
            'center' => [
                'image' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=560&q=85',
                'alt' => 'Polo inspired fragrance bottle',
            ],
            'right' => [
                'image' => 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?auto=format&fit=crop&w=500&q=80',
                'alt' => 'Premium fragrance bottle detail',
            ],
        ],
    ],
    [
        'name' => 'Adidas',
        'count' => 48,
        'stack' => [
            'left' => [
                'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=500&q=80',
                'alt' => 'Sport sneaker product angle',
            ],
            'center' => [
                'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=560&q=85',
                'alt' => 'Adidas inspired sneaker in rose color',
            ],
            'right' => [
                'image' => 'https://images.unsplash.com/photo-1516826957135-700dedea698c?auto=format&fit=crop&w=500&q=80',
                'alt' => 'Sporty fashion outfit styling',
            ],
        ],
    ],
];

$activeBrandIndex = 0;

foreach ($brandRanking as $index => $brand) {
    if (!empty($brand['active'])) {
        $activeBrandIndex = $index;
        break;
    }
}

$activeBrand = $brandRanking[$activeBrandIndex];

$categoryCards = [
    [
        'label' => 'Perfume',
        'brand' => 'Basmni',
        'image' => 'https://static.wixstatic.com/media/7187d3_912f04d78e424ab98bd1bf0decaa5c72~mv2.png/v1/fill/w_560,h_746,al_c,q_90,usm_0.66_1.00_0.01,enc_avif,quality_auto/7187d3_912f04d78e424ab98bd1bf0decaa5c72~mv2.png',
        'class' => 'category-card--tall',
    ],
    [
        'label' => 'Clothes',
        'brand' => 'Nike',
        'image' => 'https://static.nike.com/a/images/f_auto,cs_srgb/w_1536,c_limit/5feaa9c2-a959-4986-872a-54ab79f32485/nike-lookbook.jpg',
        'class' => 'category-card--high',
    ],
    [
        'label' => 'Bag',
        'brand' => 'Polo',
        'image' => 'https://assets.vogue.com/photos/66f8397bb531aa4c6be8a91b/master/w_2560%2Cc_limit/00017-polo-ralph-lauren-spring-2025-ready-to-wear-detail-credit-brand.jpg',
        'class' => 'category-card--mid',
    ],
    [
        'label' => 'Accessories',
        'brand' => 'Gucci',
        'image' => 'https://www.net-a-porter.com/variants/images/46376663162894040/ou/w2000_q60.jpg',
        'class' => 'category-card--wide',
    ],
    [
        'label' => 'Premium',
        'brand' => 'Prada',
        'image' => 'https://www.packshotfactory.co.uk/leather-goods-explorer/prada-handbag_001393_p.jpg',
        'class' => 'category-card--high',
    ],
];

$products = [
    [
        'name' => 'Midnight Puffer',
        'brand' => 'Nike',
        'description' => 'Technical layer for city movement.',
        'price' => 189,
        'image' => 'https://images.unsplash.com/photo-1543076447-215ad9ba6923?auto=format&fit=crop&w=620&q=80',
    ],
    [
        'name' => 'Orange Stitch Tote',
        'brand' => 'Gucci',
        'description' => 'Structured carry with a bright finish.',
        'price' => 246,
        'image' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=620&q=80',
    ],
    [
        'name' => 'Polo Night Pour',
        'brand' => 'Polo',
        'description' => 'Warm fragrance for late evenings.',
        'price' => 96,
        'image' => 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?auto=format&fit=crop&w=620&q=80',
    ],
    [
        'name' => 'Runner Rose',
        'brand' => 'Adidas',
        'description' => 'Soft runner with everyday comfort.',
        'price' => 132,
        'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=620&q=80',
    ],
];

$menProducts = [
    [
        'name' => 'Core Knit Polo',
        'brand' => 'Polo',
        'description' => 'Clean collar layer with a soft handfeel.',
        'price' => 118,
        'image' => 'https://images.unsplash.com/photo-1516257984-b1b4d707412e?auto=format&fit=crop&w=620&q=80',
    ],
    [
        'name' => 'City Runner Set',
        'brand' => 'Nike',
        'description' => 'Lightweight pieces built for motion.',
        'price' => 174,
        'image' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=620&q=80',
    ],
    [
        'name' => 'Classic Denim Layer',
        'brand' => 'Adidas',
        'description' => 'Everyday denim with premium structure.',
        'price' => 142,
        'image' => 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?auto=format&fit=crop&w=620&q=80',
    ],
    [
        'name' => 'Black Utility Bag',
        'brand' => 'Gucci',
        'description' => 'Compact carry for daily essentials.',
        'price' => 260,
        'image' => 'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?auto=format&fit=crop&w=620&q=80',
    ],
];

$womenProducts = [
    [
        'name' => 'Soft Crop Hoodie',
        'brand' => 'Nike',
        'description' => 'Relaxed fleece with a sculpted fit.',
        'price' => 128,
        'image' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=620&q=80',
    ],
    [
        'name' => 'Evening Mini Tote',
        'brand' => 'Gucci',
        'description' => 'Bright structured bag for polished looks.',
        'price' => 286,
        'image' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=620&q=80',
    ],
    [
        'name' => 'Rose Signature Pour',
        'brand' => 'Chanel',
        'description' => 'Fresh floral notes for day-to-night wear.',
        'price' => 156,
        'image' => 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?auto=format&fit=crop&w=620&q=80',
    ],
    [
        'name' => 'Pearl Sport Sneaker',
        'brand' => 'Adidas',
        'description' => 'Soft runner with a clean profile.',
        'price' => 138,
        'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=620&q=80',
    ],
];

$productAccordions = [
    'man' => [
        'label' => 'Man',
        'products' => $menProducts,
    ],
    'woman' => [
        'label' => 'Woman',
        'products' => $womenProducts,
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
    <link rel="stylesheet" href="assets/css/styles.css?v=48">
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
                <img src="https://wallpapers.com/images/hd/high-top-nike-sneakers-png-plu11-rhcegylkjkwb4db9.png" alt="High top Nike sneakers">
            </figure>

            <div class="hero-sidecopy">
                <p>Explore many types<br>of BRAND with the best<br>stylize design</p>
                <h2>Every<br><span>where -</span></h2>
            </div>
        </section>

        <section class="brand-ticker" aria-label="Luxury brands">
            <div class="brand-track">
                <?php for ($i = 0; $i < 4; $i++): ?>
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
                    <img class="stack-img stack-img--left" data-brand-stack="left" src="<?= htmlspecialchars($activeBrand['stack']['left']['image']); ?>" alt="<?= htmlspecialchars($activeBrand['stack']['left']['alt']); ?>">
                    <img class="stack-img stack-img--center" data-brand-stack="center" src="<?= htmlspecialchars($activeBrand['stack']['center']['image']); ?>" alt="<?= htmlspecialchars($activeBrand['stack']['center']['alt']); ?>">
                    <img class="stack-img stack-img--right" data-brand-stack="right" src="<?= htmlspecialchars($activeBrand['stack']['right']['image']); ?>" alt="<?= htmlspecialchars($activeBrand['stack']['right']['alt']); ?>">
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
                                data-brand-left-image="<?= htmlspecialchars($brand['stack']['left']['image']); ?>"
                                data-brand-left-alt="<?= htmlspecialchars($brand['stack']['left']['alt']); ?>"
                                data-brand-center-image="<?= htmlspecialchars($brand['stack']['center']['image']); ?>"
                                data-brand-center-alt="<?= htmlspecialchars($brand['stack']['center']['alt']); ?>"
                                data-brand-right-image="<?= htmlspecialchars($brand['stack']['right']['image']); ?>"
                                data-brand-right-alt="<?= htmlspecialchars($brand['stack']['right']['alt']); ?>"
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

        <span class="section-anchor" id="gender" aria-hidden="true"></span>

        <section class="category-section" id="shop" aria-labelledby="category-heading">
            <section class="feature-ribbon" aria-label="Store quality highlights">
                <div class="feature-track">
                    <?php for ($i = 0; $i < 4; $i++): ?>
                        <?php foreach ($featureLine as $feature): ?>
                            <span><?= htmlspecialchars($feature); ?></span>
                        <?php endforeach; ?>
                    <?php endfor; ?>
                </div>
            </section>

            <div class="section-heading">
                <h2 id="category-heading">Explore with all<br><span>- luxuries</span></h2>
                <a href="#new" class="text-link">2026</a>
            </div>

            <div class="category-board">
                <?php foreach ($categoryCards as $card): ?>
                    <article class="category-card <?= htmlspecialchars($card['class']); ?>">
                        <a href="#new" aria-label="Shop <?= htmlspecialchars($card['label']); ?>">
                            <img src="<?= htmlspecialchars($card['image']); ?>" alt="<?= htmlspecialchars($card['label']); ?> fashion category">
                            <span class="category-title"><?= htmlspecialchars($card['label']); ?></span>
                            <span class="category-brand"><?= htmlspecialchars($card['brand']); ?></span>
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

            <div class="products-panel">
                <button
                    class="products-panel__heading"
                    type="button"
                    data-product-toggle
                    aria-expanded="true"
                    aria-controls="products-popular"
                >
                    <span>Popular</span>
                </button>

                <div class="product-panel-content product-panel-content--popular" id="products-popular" data-product-content>
                    <div class="product-grid">
                        <?php foreach ($products as $product): ?>
                            <article class="product-card" data-product-card data-name="<?= htmlspecialchars(strtolower($product['name'] . ' ' . $product['brand'])); ?>">
                                <a class="product-image" href="#" aria-label="View <?= htmlspecialchars($product['name']); ?>">
                                    <img src="<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                                </a>
                                <div class="product-info">
                                    <p><?= htmlspecialchars($product['brand']); ?></p>
                                    <h3><?= htmlspecialchars($product['name']); ?></h3>
                                    <span><?= htmlspecialchars($product['description']); ?></span>
                                </div>
                                <div class="product-actions">
                                    <strong>$<?= number_format($product['price'], 2); ?></strong>
                                    <button class="cart-button" type="button" data-add-to-cart>
                                        <span>Add to Cart</span>
                                        <i data-lucide="arrow-right"></i>
                                    </button>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php foreach ($productAccordions as $key => $group): ?>
                    <?php $panelId = 'products-' . $key; ?>
                    <button
                        class="product-panel-row"
                        type="button"
                        data-product-toggle
                        aria-expanded="false"
                        aria-controls="<?= htmlspecialchars($panelId); ?>"
                    >
                        <span><?= htmlspecialchars($group['label']); ?></span>
                        <i data-lucide="chevron-up"></i>
                    </button>
                    <div class="product-panel-content" id="<?= htmlspecialchars($panelId); ?>" data-product-content hidden>
                        <div class="product-grid product-grid--nested">
                            <?php foreach ($group['products'] as $product): ?>
                                <article class="product-card" data-product-card data-name="<?= htmlspecialchars(strtolower($product['name'] . ' ' . $product['brand'] . ' ' . $group['label'])); ?>">
                                    <a class="product-image" href="#" aria-label="View <?= htmlspecialchars($product['name']); ?>">
                                        <img src="<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                                    </a>
                                    <div class="product-info">
                                        <p><?= htmlspecialchars($product['brand']); ?></p>
                                        <h3><?= htmlspecialchars($product['name']); ?></h3>
                                        <span><?= htmlspecialchars($product['description']); ?></span>
                                    </div>
                                    <div class="product-actions">
                                        <strong>$<?= number_format($product['price'], 2); ?></strong>
                                        <button class="cart-button" type="button" data-add-to-cart>
                                            <span>Add to Cart</span>
                                            <i data-lucide="arrow-right"></i>
                                        </button>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <a class="brand-mark brand-mark--footer" href="#home">the DS</a>
        <p><span>- Fabric Luxury</span><br>and Premium</p>
    </footer>

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="assets/js/app.js?v=9"></script>
</body>
</html>
