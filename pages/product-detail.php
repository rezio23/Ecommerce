<?php

require '../includes/security.php';
startSecureSession();
require '../includes/db.php';

function getProductSlug(string $name): string
{
    return trim((string) preg_replace('/[^a-z0-9]+/', '-', strtolower($name)), '-');
}

function getProductCategory(array $product): string
{
    $tags = array_map('strtolower', $product['tags'] ?? []);
    $audience = in_array('woman', $tags, true) ? "Women's" : "Men's";

    if (in_array('fragrance', $tags, true)) {
        return $audience . ' Perfume';
    }

    if (in_array('bag', $tags, true)) {
        return 'Luxury Bag';
    }

    if (in_array('sneaker', $tags, true)) {
        return $audience . ' Sneaker';
    }

    if (in_array('shoes', $tags, true)) {
        return $audience . ' Shoes';
    }

    if (in_array('jacket', $tags, true)) {
        return $audience . ' Jacket';
    }

    if (in_array('polo', $tags, true)) {
        return $audience . ' Polo Shirt';
    }

    return 'Premium Product';
}

function getProductSizes(array $product): array
{
    $tags = array_map('strtolower', $product['tags'] ?? []);

    if (in_array('fragrance', $tags, true)) {
        return ['30ML', '50ML', '90ML', '100ML', '150ML', 'Refill'];
    }

    if (in_array('bag', $tags, true)) {
        return ['Mini', 'Small', 'Medium', 'Large', 'XL', 'One Size'];
    }

    if (in_array('sneaker', $tags, true) || in_array('shoes', $tags, true)) {
        return ['36', '37', '38', '39', '40', '41', '42', '43', '44', '45'];
    }

    return ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
}

function getDefaultActiveSize(array $product, array $sizes): string
{
    $tags = array_map('strtolower', $product['tags'] ?? []);

    if (in_array('fragrance', $tags, true)) {
        return '100ML';
    }

    if (in_array('bag', $tags, true)) {
        return 'Medium';
    }

    if (in_array('sneaker', $tags, true) || in_array('shoes', $tags, true)) {
        return '40';
    }

    return 'M';
}

function getSizePriceMultiplier(string $size): float
{
    $fragranceMap = [
        '30ML' => 0.50,
        '50ML' => 0.70,
        '90ML' => 0.90,
        '100ML' => 1.00,
        '150ML' => 1.30,
        'Refill' => 0.60,
    ];

    $bagMap = [
        'Mini' => 0.70,
        'Small' => 0.85,
        'Medium' => 1.00,
        'Large' => 1.20,
        'XL' => 1.40,
        'One Size' => 1.00,
    ];

    if (isset($fragranceMap[$size])) {
        return $fragranceMap[$size];
    }

    if (isset($bagMap[$size])) {
        return $bagMap[$size];
    }

    return 1.00;
}

$stmt = $pdo->query('SELECT * FROM products ORDER BY id');
$productCatalog = [];

while ($row = $stmt->fetch()) {
    $slug = $row['slug'] ?? getProductSlug($row['name']);
    $row['tags'] = array_map('trim', explode(',', $row['tags'] ?? ''));
    $row['gallery'] = array_filter(explode('|', $row['gallery'] ?? ''));
    $row['slug'] = $slug;
    $productCatalog[$slug] = $row;
}

$rawSlug = getGet('product');
$currentSlug = $rawSlug !== '' ? getProductSlug($rawSlug) : 'paradigme-eau-de-parfum';
$product = $productCatalog[$currentSlug] ?? $productCatalog['paradigme-eau-de-parfum'];
$product['category'] = getProductCategory($product);
$product['sizes'] = getProductSizes($product);
$product['active_size'] = getDefaultActiveSize($product, $product['sizes']);

$isFavorited = false;
if (isset($_SESSION['user_id']) && !empty($product['id'])) {
    $favCheck = $pdo->prepare('SELECT id FROM favorites WHERE user_id = :user_id AND product_id = :product_id');
    $favCheck->execute([':user_id' => (int) $_SESSION['user_id'], ':product_id' => (int) $product['id']]);
    $isFavorited = (bool) $favCheck->fetch();
}

$productGallery = array_map(
    fn (string $image): array => [
        'image' => $image,
        'alt' => $product['name'] . ' product image',
    ],
    $product['gallery']
);

$similarProducts = array_values(array_filter($productCatalog, function (array $catalogProduct) use ($product): bool {
    if ($catalogProduct['slug'] === $product['slug']) {
        return false;
    }

    return $catalogProduct['brand'] === $product['brand']
        || count(array_intersect($catalogProduct['tags'], $product['tags'])) > 0;
}));

if (count($similarProducts) < 5) {
    foreach ($productCatalog as $catalogProduct) {
        if ($catalogProduct['slug'] !== $product['slug'] && !in_array($catalogProduct, $similarProducts, true)) {
            $similarProducts[] = $catalogProduct;
        }
    }
}

$similarProducts = array_slice($similarProducts, 0, 5);
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
    <link rel="stylesheet" href="../assets/css/styles.css?v=97">
</head>
<body class="product-detail-page">
    

<?php
$headerId = 'product-top';
$searchId = 'header-product-search';
$bagCount = 0;
$activeButton = '';
$currentPage = 'shop';
$searchTrigger = 'button';
$rootPath = '../';
$srcPath = '';
?>

<?php include '../includes/navbar.php'; ?>
<main class="product-detail-main">
        <nav class="product-breadcrumb" aria-label="Breadcrumb">
            <a href="shop.php">Shop</a>
            <span>/</span>
            <a href="shop.php#shop-grid"><?= htmlspecialchars(str_replace("'s", '', $product['category'])); ?></a>
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
                    <form action="cart-action.php" method="post" class="detail-cart-form" style="display:inline;">
                        <?= csrfField(); ?>
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="slug" value="<?= htmlspecialchars($product['slug'] ?? ''); ?>">
                        <input type="hidden" name="size" value="<?= htmlspecialchars($product['active_size']); ?>" id="detail-cart-size">
                        <button class="product-primary-button" type="submit" data-add-to-cart>
                            <span>Add to Cart</span>
                        </button>
                    </form>
                    <form action="favorite-action.php" method="post" style="display:inline;">
                        <?= csrfField(); ?>
                        <input type="hidden" name="product_id" value="<?= (int) ($product['id'] ?? 0); ?>">
                        <button class="product-secondary-button" type="submit"><?= $isFavorited ? 'Unfavorite' : 'Favorite'; ?></button>
                    </form>
                </div>
            </div>

            <article class="product-detail-info">
                <p class="product-detail-brand"><?= htmlspecialchars($product['brand']); ?></p>
                <h1 id="product-title"><?= htmlspecialchars($product['name']); ?></h1>
                <p class="product-detail-description"><?= htmlspecialchars($product['description']); ?></p>
                <p class="product-detail-category"><?= htmlspecialchars($product['category']); ?></p>
                <p class="product-detail-price" data-base-price="<?= (float) $product['price']; ?>" data-size-price="<?= number_format((float) $product['price'] * getSizePriceMultiplier($product['active_size']), 2); ?>">$ <?= number_format((float) $product['price'] * getSizePriceMultiplier($product['active_size']), 2); ?></p>

                <section class="product-option-group" aria-labelledby="product-size-title">
                    <h2 id="product-size-title">Size</h2>
                    <div class="product-size-grid" data-product-size-group>
                        <?php foreach ($product['sizes'] as $size): ?>
                            <button
                                class="<?= $size === $product['active_size'] ? 'is-active' : ''; ?>"
                                type="button"
                                data-product-size-option
                                data-size-value="<?= htmlspecialchars($size); ?>"
                                data-size-multiplier="<?= getSizePriceMultiplier($size); ?>"
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
                            <?php $similarImage = $similarProduct['gallery'][0] ?? ''; ?>
                            <a
                                href="product-detail.php?product=<?= rawurlencode($similarProduct['slug']); ?>"
                                aria-label="View <?= htmlspecialchars($similarProduct['name']); ?>"
                                title="<?= htmlspecialchars($similarProduct['name']); ?>"
                            >
                                <img src="<?= htmlspecialchars($similarImage); ?>" alt="<?= htmlspecialchars($similarProduct['name']); ?>">
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>
            </article>
        </section>
    </main>

    

<?php include '../includes/footer.php'; ?>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/app.js?v=24"></script>
</body>
</html>
