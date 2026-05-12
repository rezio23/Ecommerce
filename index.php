<?php

require 'includes/security.php';
require 'includes/db.php';

$stmt = $pdo->query('SELECT * FROM products ORDER BY id');
$allProducts = [];

while ($row = $stmt->fetch()) {
    $row['tags'] = array_map('trim', explode(',', $row['tags'] ?? ''));
    $allProducts[] = $row;
}

$products = array_values(array_filter($allProducts, function ($p) {
    return in_array('Popular', $p['tags'], true);
}));

$menProducts = array_values(array_filter($allProducts, function ($p) {
    return in_array('Man', $p['tags'], true);
}));

$womenProducts = array_values(array_filter($allProducts, function ($p) {
    return in_array('Woman', $p['tags'], true);
}));

$brandBadges = [
    [
        'name' => 'polo',
        'abbr' => 'POLO',
        'logo' => 'https://static.vecteezy.com/system/resources/previews/023/867/295/non_2x/polo-brand-logo-white-symbol-clothes-design-icon-abstract-illustration-with-black-background-free-vector.jpg',
    ],
    [
        'name' => 'nike',
        'abbr' => 'NIKE',
        'logo' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQHLm_ETnATw3cjxh5JahsJfORsm6HzZts5VA&s',
    ],
    [
        'name' => 'adidas',
        'abbr' => 'ADIDAS',
        'logo' => 'https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/28549a58-638c-4112-81a9-ab45e3bb4453/dg0ugic-e8f7c206-aa5e-4cf5-afdb-ba5c97554682.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiIvZi8yODU0OWE1OC02MzhjLTQxMTItODFhOS1hYjQ1ZTNiYjQ0NTMvZGcwdWdpYy1lOGY3YzIwNi1hYTVlLTRjZjUtYWZkYi1iYTVjOTc1NTQ2ODIuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.2wxaDYrhrd1rwunDfAhi4ooNzr_ZzkRLCwXmW9xaVyQ',
    ],
];

$tickerBrands = ['POLO', 'BALENCIAGA', 'adidas', 'NIKE', 'PUMA', 'GUCCI', 'POLO', 'VERSACE'];

$brandRanking = [
    [
        'name' => 'Balenciaga',
        'count' => 48,
        'stack' => [
            'left' => [
                'image' => 'https://www.stylerave.com/wp-content/uploads/2025/05/balenciaga-le-city-bag-ezgif.com-avif-to-jpg-converter-1.jpg',
                'alt' => 'Balenciaga Le City bag Spring 2026',
            ],
            'center' => [
                'image' => 'https://image-cdn.hypb.st/https%3A%2F%2Fhypebeast.com%2Fimage%2F2026%2F02%2F17%2Fpierpaolo-piccioli-debut-balenciaga-heart-and-body-campaign-hudson-williams-winona-ryder-harris-dickinson-001.jpg?q=75&w=1200&cbr=1&fit=max',
                'alt' => 'Balenciaga Heart and Body Spring 2026 campaign',
            ],
            'right' => [
                'image' => 'https://image-cdn.hypb.st/https%3A%2F%2Fhypebeast.com%2Fimage%2F2026%2F03%2F16%2Fbalenciaga-radar-sneaker-ballerina-release-info.jpg?q=75&w=1200&cbr=1&fit=max',
                'alt' => 'Balenciaga Radar sneaker 2026',
            ],
        ],
    ],
    [
        'name' => 'Gucci',
        'count' => 12,
        'stack' => [
            'left' => [
                'image' => 'https://images.squarespace-cdn.com/content/v1/59b2777f49fc2b50d073cb2b/1772522023035-35BKTMVFZURWQ4MDOIH6/1000247973.jpg',
                'alt' => 'Gucci FW26 Primavera runway look',
            ],
            'center' => [
                'image' => 'https://images.squarespace-cdn.com/content/v1/59b2777f49fc2b50d073cb2b/1772522023236-M1OSFIRE8G768JV986J6/1000247976.jpg',
                'alt' => 'Gucci Fall 2026 collection by Demna',
            ],
            'right' => [
                'image' => 'https://images.squarespace-cdn.com/content/v1/59b2777f49fc2b50d073cb2b/1772522026051-6O4AVQSF4K4Y9ZYT95ZC/1000247979.jpg',
                'alt' => 'Gucci FW26 runway detail',
            ],
        ],
    ],
    [
        'name' => 'Nike',
        'count' => 48,
        'stack' => [
            'left' => [
                'image' => 'https://image-cdn.hypb.st/https%3A%2F%2Fhypebeast.com%2Fimage%2F2026%2F03%2F10%2Fnike-air-liquid-max-announcement-info-1.jpg?q=75&w=1200&cbr=1&fit=max',
                'alt' => 'Nike Air Liquid Max 2026 product',
            ],
            'center' => [
                'image' => 'https://image-cdn.hypb.st/https%3A%2F%2Fhypebeast.com%2Fimage%2F2026%2F03%2F10%2Fnike-air-liquid-max-announcement-info-3.jpg?q=75&w=1200&cbr=1&fit=max',
                'alt' => 'Nike Air Liquid Max lifestyle 2026',
            ],
            'right' => [
                'image' => 'https://image-cdn.hypb.st/https%3A%2F%2Fhypebeast.com%2Fimage%2F2026%2F03%2F10%2Fnike-air-liquid-max-announcement-info-5.jpg?q=75&w=1200&cbr=1&fit=max',
                'alt' => 'Nike Air Liquid Max detail 2026',
            ],
        ],
        'active' => true,
    ],
    [
        'name' => 'Polo',
        'count' => 32,
        'stack' => [
            'left' => [
                'image' => 'https://images.pexels.com/photos/16048133/pexels-photo-16048133.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
                'alt' => 'Polo Ralph Lauren style model',
            ],
            'center' => [
                'image' => 'https://images.pexels.com/photos/7270145/pexels-photo-7270145.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
                'alt' => 'Preppy polo fashion portrait',
            ],
            'right' => [
                'image' => 'https://trendygolfusa.com/cdn/shop/files/LAUNCHES_HERO_7c49c26e-fc63-4418-a7d4-2d4b3d44ece2.jpg?v=1689281730',
                'alt' => 'Ralph Lauren golf lifestyle',
            ],
        ],
    ],
    [
        'name' => 'Adidas',
        'count' => 48,
        'stack' => [
            'left' => [
                'image' => 'https://justfreshkicks.com/wp-content/uploads/2026/04/wales-bonner-adidas-summer-2026-collection-release-date.jpg',
                'alt' => 'Adidas Wales Bonner SS26 collection',
            ],
            'center' => [
                'image' => 'https://justfreshkicks.com/wp-content/uploads/2026/05/adistar-control-5-PR-scaled.jpg',
                'alt' => 'Adidas Adistar Control 5 2026',
            ],
            'right' => [
                'image' => 'https://justfreshkicks.com/wp-content/uploads/2026/05/adidas-bw-run-set-scaled.jpg',
                'alt' => 'Adidas BW Run 2026',
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

function getProductDetailHref(array $product): string
{
    $slug = trim((string) preg_replace('/[^a-z0-9]+/', '-', strtolower($product['name'])), '-');

    return 'pages/product-detail.php?product=' . rawurlencode($slug);
}

function getCategoryHref(string $label): string
{
    $map = [
        'Perfume' => 'perfumes',
        'Clothes' => 'clothes',
        'Bag' => 'bags',
        'Accessories' => 'accessories',
        'Premium' => 'premium',
    ];

    if (isset($map[$label])) {
        return 'pages/shop.php?category=' . $map[$label] . '#shop-grid';
    }

    return 'pages/shop.php#shop-grid';
}
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
    <link rel="stylesheet" href="assets/css/styles.css?v=89">
</head>
<body>
    

<?php
$headerId = 'home';
$searchId = 'header-product-search';
$bagCount = 0;
$activeButton = '';
$currentPage = 'home';
$searchTrigger = 'button';
$rootPath = '';
$srcPath = 'pages/';
?>

<?php include 'includes/navbar.php'; ?>
<main>
        <section class="hero-section" aria-labelledby="hero-heading">
            <div class="hero-copy">
                <p class="pixel-note">/New Arrival<br>Collection 2026</p>
                <h1 id="hero-heading">Stylish your <span>- Fashion</span></h1>

                <div class="brand-badges" aria-label="Featured brands">
                    <?php foreach ($brandBadges as $badge): ?>
                        <span class="brand-badge" title="<?= htmlspecialchars($badge['name']); ?>">
                            <img id="banner_main" src="<?= htmlspecialchars($badge['logo']); ?>" alt="<?= htmlspecialchars($badge['abbr']); ?> logo">
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
                <p id="home_text">Explore many types<br>of BRAND with the best<br>stylize design</p>
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
                <a class="outline-cta" href="pages/shop.php?brand=<?= htmlspecialchars(strtolower($activeBrand['name']) === 'polo' ? 'ralph-lauren' : strtolower($activeBrand['name'])); ?>#brand_selector" data-see-product>
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
                                data-brand-filter="<?= htmlspecialchars(strtolower($brand['name']) === 'polo' ? 'ralph-lauren' : strtolower($brand['name'])); ?>"
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
                        <a href="<?= htmlspecialchars(getCategoryHref($card['label'])); ?>" aria-label="Shop <?= htmlspecialchars($card['label']); ?>">
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
                <input id="product-search" data-product-search type="search" placeholder="Try Nike, bag, puffer...">
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
                    <i data-lucide="chevron-down" aria-hidden="true"></i>
                </button>

                <div class="product-panel-content product-panel-content--popular" id="products-popular" data-product-content>
                    <div class="product-grid">
                        <?php foreach (array_slice($products, 0, 4) as $product): ?>
                            <?php $productTags = $product['tags'] ?? []; ?>
                            <?php $productTagText = implode(' ', $productTags); ?>
                            <?php $productHref = getProductDetailHref($product); ?>
                            <article
                                class="product-card"
                                data-product-card
                                data-name="<?= htmlspecialchars(strtolower($product['name'] . ' ' . $product['brand'] . ' ' . $productTagText)); ?>"
                                data-tags="<?= htmlspecialchars(strtolower($productTagText)); ?>"
                            >
                                <a class="product-image" href="<?= htmlspecialchars($productHref); ?>" aria-label="View <?= htmlspecialchars($product['name']); ?>">
                                    <img src="<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                                </a>
                                <div class="product-info">
                                    <p><?= htmlspecialchars($product['brand']); ?></p>
                                    <h3><?= htmlspecialchars($product['name']); ?></h3>
                                    <span><?= htmlspecialchars($product['description']); ?></span>
                                </div>
                                <?php if (!empty($productTags)): ?>
                                    <div class="product-tags" aria-label="Product tags">
                                        <?php foreach ($productTags as $tag): ?>
                                            <span><?= htmlspecialchars($tag); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="product-actions">
                                    <strong>$<?= number_format($product['price'], 2); ?></strong>
                                    <form action="pages/cart-action.php" method="post" style="display:inline;">
                                        <?= csrfField(); ?>
                                        <input type="hidden" name="action" value="add">
                                        <input type="hidden" name="slug" value="<?= htmlspecialchars($product['slug'] ?? ''); ?>">
                                        <button class="cart-button" type="submit" data-add-to-cart>
                                            <span>Add to Cart</span>
                                            <i data-lucide="arrow-right"></i>
                                        </button>
                                    </form>
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
                        <i data-lucide="chevron-down" aria-hidden="true"></i>
                    </button>
                    <div class="product-panel-content" id="<?= htmlspecialchars($panelId); ?>" data-product-content hidden>
                        <div class="product-grid product-grid--nested">
                            <?php foreach (array_slice($group['products'], 0, 4) as $product): ?>
                                <?php $productTags = $product['tags'] ?? []; ?>
                                <?php $productTagText = implode(' ', $productTags); ?>
                                <?php $productHref = getProductDetailHref($product); ?>
                                <article
                                    class="product-card"
                                    data-product-card
                                    data-name="<?= htmlspecialchars(strtolower($product['name'] . ' ' . $product['brand'] . ' ' . $group['label'] . ' ' . $productTagText)); ?>"
                                    data-tags="<?= htmlspecialchars(strtolower($productTagText)); ?>"
                                >
                                    <a class="product-image" href="<?= htmlspecialchars($productHref); ?>" aria-label="View <?= htmlspecialchars($product['name']); ?>">
                                        <img src="<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                                    </a>
                                    <div class="product-info">
                                        <p><?= htmlspecialchars($product['brand']); ?></p>
                                        <h3><?= htmlspecialchars($product['name']); ?></h3>
                                        <span><?= htmlspecialchars($product['description']); ?></span>
                                    </div>
                                    <?php if (!empty($productTags)): ?>
                                        <div class="product-tags" aria-label="Product tags">
                                            <?php foreach ($productTags as $tag): ?>
                                                <span><?= htmlspecialchars($tag); ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
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

    

<?php include 'includes/footer.php'; ?>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="assets/js/app.js?v=22"></script>
</body>
</html>
