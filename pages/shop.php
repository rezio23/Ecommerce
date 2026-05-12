<?php

require '../includes/security.php';
require '../includes/db.php';

$stmt = $pdo->query('SELECT * FROM products ORDER BY id');
$shopProducts = [];

while ($row = $stmt->fetch()) {
    $row['tags'] = array_map('trim', explode(',', $row['tags'] ?? ''));
    $shopProducts[] = $row;
}

$shopBrandOptions = [
    ['label' => 'All brands', 'value' => ''],
    ['label' => 'Nike', 'value' => 'nike'],
    ['label' => 'Prada', 'value' => 'prada'],
    ['label' => 'Balenciaga', 'value' => 'balenciaga'],
    ['label' => 'Ralph Lauren', 'value' => 'ralph-lauren'],
    ['label' => 'Puma', 'value' => 'puma'],
    ['label' => 'Chanel', 'value' => 'chanel'],
    ['label' => 'Gucci', 'value' => 'gucci'],
    ['label' => 'Adidas', 'value' => 'adidas'],
];

$shopAudienceOptions = [
    ['label' => 'All', 'value' => ''],
    ['label' => 'Man', 'value' => 'man'],
    ['label' => 'Woman', 'value' => 'woman'],
    ['label' => 'Kid', 'value' => 'kid'],
];

function getShopBrandFilter(string $brand): string
{
    $brand = strtolower($brand);

    $brandMap = [
        'ralph' => 'ralph-lauren',
        'polo' => 'ralph-lauren',
        'balenciaga' => 'balenciaga',
        'chanel' => 'chanel',
        'gucci' => 'gucci',
        'nike' => 'nike',
        'prada' => 'prada',
        'puma' => 'puma',
        'adidas' => 'adidas',
    ];

    foreach ($brandMap as $needle => $value) {
        if (str_contains($brand, $needle)) {
            return $value;
        }
    }

    return trim((string) preg_replace('/[^a-z0-9]+/', '-', $brand), '-');
}

function getShopAudienceFilter(array $tags): string
{
    $normalizedTags = array_map('strtolower', $tags);

    foreach (['man', 'woman', 'kid'] as $audience) {
        if (in_array($audience, $normalizedTags, true)) {
            return $audience;
        }
    }

    return '';
}

function getShopFilterGroups(array $product): string
{
    $tags = array_map('strtolower', $product['tags'] ?? []);
    $groups = [];

    if (in_array('popular', $tags, true)) {
        $groups[] = 'popular';
    } else {
        $groups[] = 'new-drops';
    }

    if (in_array('fragrance', $tags, true)) {
        $groups[] = 'perfumes';
    }

    if (in_array('bag', $tags, true)) {
        $groups[] = 'bags';
    }

    if (in_array('sneaker', $tags, true) || in_array('shoes', $tags, true)) {
        $groups[] = 'sneakers';
    }

    if (count(array_intersect($tags, ['classic', 'polo', 'jacket', 'sport', 'streetwear'])) > 0) {
        $groups[] = 'clothes';
    }

    if (in_array('luxury', $tags, true)) {
        $groups[] = 'premium';
    }

    if (in_array('accessory', $tags, true) || in_array('accessories', $tags, true)) {
        $groups[] = 'accessories';
    }

    return implode(' ', array_values(array_unique($groups)));
}

function getProductDetailHref(array $product): string
{
    $slug = trim((string) preg_replace('/[^a-z0-9]+/', '-', strtolower($product['name'])), '-');

    return 'product-detail.php?product=' . rawurlencode($slug);
}

function renderShopProductCard(array $product, bool $filterable = true): void
{
    $productTags = $product['tags'] ?? [];
    $productTagText = implode(' ', $productTags);
    $dataAttributes = '';
    $productHref = getProductDetailHref($product);

    if ($filterable) {
        $dataAttributes = sprintf(
            ' data-product-card data-name="%s" data-tags="%s" data-brand="%s" data-audience="%s" data-groups="%s"',
            htmlspecialchars(strtolower($product['name'] . ' ' . $product['brand'] . ' ' . $productTagText)),
            htmlspecialchars(strtolower($productTagText)),
            htmlspecialchars(getShopBrandFilter($product['brand'])),
            htmlspecialchars(getShopAudienceFilter($productTags)),
            htmlspecialchars(getShopFilterGroups($product))
        );
    }
?>
    <article class="product-card" <?= $dataAttributes; ?>>
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
            <form action="cart-action.php" method="post" style="display:inline;">
                <?= csrfField(); ?>
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="slug" value="<?= htmlspecialchars($product['slug'] ?? getProductSlug($product['name'])); ?>">
                <button class="cart-button" type="submit" data-add-to-cart>
                    <span>Add to Cart</span>
                    <i data-lucide="arrow-right"></i>
                </button>
            </form>
        </div>
    </article>
<?php
}

$shopHighlights = [
    [
        'icon' => 'badge-check',
        'title' => 'Verified Brands',
        'text' => 'Every piece is curated around trusted premium labels.',
    ],
    [
        'icon' => 'sparkles',
        'title' => 'Fresh Rotation',
        'text' => 'New drops keep the collection moving with the season.',
    ],
    [
        'icon' => 'shield-check',
        'title' => 'Checkout Care',
        'text' => 'Clear pricing, clean browsing, and quick cart actions.',
    ],
];

$shopProductsPerPage = 8;

$featureLine = ['PREMIUM FABRIC', 'MODERN LIFESTYLE', 'FABRIC QUALITY', 'TIMELESS CUTS', 'CLASSIC AND COMFORT'];
$shopPageCount = max(1, (int) ceil(count($shopProducts) / $shopProductsPerPage));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop | The DS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Doto:wght@400;600;700;800&family=Krona+One&family=Modak&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css?v=94">
</head>

<body class="shop-page">


    <?php
    $headerId = 'shop-top';
    $searchId = 'header-product-search';
    $bagCount = 0;
    $activeButton = '';
    $currentPage = 'shop';
    $searchTrigger = 'button';
    $rootPath = '../';
    $srcPath = '';
    ?>

    <?php include '../includes/navbar.php'; ?>
    <main class="shop-main">
        <section class="shop-hero" aria-label="Luxury fragrance shop banner">
            <figure class="shop-hero-model">
                <img src="https://www.pngall.com/wp-content/uploads/13/Nike-Shoes-Air-Max-PNG-Images.png" alt="Nike Air Max shoes">
            </figure>

            <div class="shop-hero-copy">
                <p class="pixel-note">/2026 Collection<br>New Arrivals</p>
                <h1>Shop the<br><span>- Best Brands</span></h1>
                <p class="hero-subtitle">Curated premium pieces<br>ready for checkout.</p>
            </div>
        </section>

        <section class="shop-collection" aria-labelledby="shop-heading">

            <section class="shop-catalog" id="shop-grid" aria-labelledby="catalog-heading">

                <section class="feature-ribbon" aria-label="Store quality highlights">
                    <div class="feature-track">
                        <?php for ($i = 0; $i < 4; $i++): ?>
                            <?php foreach ($featureLine as $feature): ?>
                                <span><?= htmlspecialchars($feature); ?></span>
                            <?php endforeach; ?>
                        <?php endfor; ?>
                    </div>
                </section>
                <div class="shop-catalog__heading">
                    <div>
                        <h2 id="catalog-heading">All Products</h2>
                        <p>Browse the full edit with category, brand, and audience filters.</p>
                    </div>
                    <span><?= count($shopProducts); ?> pieces</span>
                </div>

                <div class="search-panel shop-search-panel" hidden>
                    <label for="product-search">Search collection</label>
                    <input id="product-search" data-product-search type="search" placeholder="Try Nike, bag, perfume...">
                </div>

                <div class="shop-toolbar">
                    <nav class="shop-filter-row" aria-label="Shop categories">
                        <button class="is-active" type="button" data-product-group-filter data-filter-value="" aria-pressed="true">All</button>
                        <button type="button" data-product-group-filter data-filter-value="popular" aria-pressed="false">Popular</button>
                        <button type="button" data-product-group-filter data-filter-value="new-drops" aria-pressed="false">New Drops</button>
                        <button type="button" data-product-group-filter data-filter-value="clothes" aria-pressed="false">Clothes</button>
                        <button type="button" data-product-group-filter data-filter-value="perfumes" aria-pressed="false">Perfumes</button>
                        <button type="button" data-product-group-filter data-filter-value="bags" aria-pressed="false">Bags</button>
                        <button type="button" data-product-group-filter data-filter-value="sneakers" aria-pressed="false">Sneakers</button>
                        <button type="button" data-product-group-filter data-filter-value="accessories" aria-pressed="false">Accessories</button>
                        <button type="button" data-product-group-filter data-filter-value="premium" aria-pressed="false">Premium</button>
                    </nav>

                    <div class="shop-selectors" aria-label="Shop filters">
                        <div class="shop-select-control" data-filter-select>
                            <span id="brand_selector" class="shop-select-control__label">Brand</span>
                            <button
                                class="shop-select-toggle"
                                type="button"
                                data-filter-toggle
                                data-product-brand-filter
                                data-filter-value=""
                                aria-haspopup="listbox"
                                aria-expanded="false"
                                aria-controls="shop-brand-list">
                                <span data-filter-current>All brands</span>
                                <i data-lucide="chevron-down"></i>
                            </button>
                            <div class="shop-select-menu" id="shop-brand-list" role="listbox" aria-label="Select brand">
                                <?php foreach ($shopBrandOptions as $index => $option): ?>
                                    <button
                                        class="<?= $index === 0 ? 'is-selected' : ''; ?>"
                                        type="button"
                                        role="option"
                                        aria-selected="<?= $index === 0 ? 'true' : 'false'; ?>"
                                        data-filter-option
                                        data-filter-value="<?= htmlspecialchars($option['value']); ?>">
                                        <?= htmlspecialchars($option['label']); ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="shop-select-control" data-filter-select>
                            <span class="shop-select-control__label">For</span>
                            <button
                                class="shop-select-toggle"
                                type="button"
                                data-filter-toggle
                                data-product-audience-filter
                                data-filter-value=""
                                aria-haspopup="listbox"
                                aria-expanded="false"
                                aria-controls="shop-audience-list">
                                <span data-filter-current>All</span>
                                <i data-lucide="chevron-down"></i>
                            </button>
                            <div class="shop-select-menu" id="shop-audience-list" role="listbox" aria-label="Select audience">
                                <?php foreach ($shopAudienceOptions as $index => $option): ?>
                                    <button
                                        class="<?= $index === 0 ? 'is-selected' : ''; ?>"
                                        type="button"
                                        role="option"
                                        aria-selected="<?= $index === 0 ? 'true' : 'false'; ?>"
                                        data-filter-option
                                        data-filter-value="<?= htmlspecialchars($option['value']); ?>">
                                        <?= htmlspecialchars($option['label']); ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="product-grid shop-product-grid" data-shop-product-grid>
                    <?php foreach ($shopProducts as $product): ?>
                        <?php renderShopProductCard($product); ?>
                    <?php endforeach; ?>
                </div>

                <nav
                    class="shop-pagination"
                    data-shop-pagination
                    data-page-size="<?= $shopProductsPerPage; ?>"
                    aria-label="Product pages"
                    <?= $shopPageCount <= 1 ? 'hidden' : ''; ?>>
                    <?php for ($page = 1; $page <= $shopPageCount; $page++): ?>
                        <button
                            class="<?= $page === 1 ? 'is-active' : ''; ?>"
                            type="button"
                            data-product-page="<?= $page; ?>"
                            aria-label="Show product page <?= $page; ?>"
                            <?= $page === 1 ? 'aria-current="page"' : ''; ?>>
                            <?= $page; ?>
                        </button>
                    <?php endfor; ?>
                </nav>
            </section>

            <section class="shop-highlight-band" aria-label="Important shop details">
                <?php foreach ($shopHighlights as $highlight): ?>
                    <article class="shop-highlight-item">
                        <i data-lucide="<?= htmlspecialchars($highlight['icon']); ?>"></i>
                        <div>
                            <h2><?= htmlspecialchars($highlight['title']); ?></h2>
                            <p><?= htmlspecialchars($highlight['text']); ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>

        </section>
    </main>



    <?php include '../includes/footer.php'; ?>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/app.js?v=22"></script>
</body>

</html>
