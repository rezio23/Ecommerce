<?php

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

$productCatalog = [
    'blazer-mid-premium' => [
        'name' => 'Blazer Mid Premium',
        'brand' => 'Nike',
        'description' => 'Layered high-top sneaker with a vintage edge.',
        'price' => 110,
        'tags' => ['Man', 'Sneaker', 'Popular'],
        'rating' => '4.8',
        'badge' => 'New Arrival',
        'gallery' => [
            'https://www.creativeboom.com/upload/articles/34/34fc53c4c1a50ddea6bbb35a25186d2f4bf17262_944.jpg',
            'https://content.deadstock.de/media/pages/uploads/2021/07/ea757af117-1731408448/nike-sb-blazer-mid-mosaic-brown-da8854-600-dead-stock-1-1024x719-1920x.webp',
            'https://content.deadstock.de/media/pages/uploads/2021/07/ed954481d6-1731408448/nike-sb-blazer-mid-mosaic-brown-da8854-600-dead-stock-9-1024x1024-1920x.webp',
            'https://content.deadstock.de/media/pages/uploads/2021/07/714faa9af9-1731408448/nike-sb-blazer-mid-mosaic-brown-da8854-600-dead-stock-8-1024x1024-1920x.webp',
        ],
    ],
    'paradigme-eau-de-parfum' => [
        'name' => 'Paradigme Eau de Parfum',
        'brand' => 'Prada',
        'description' => 'Ambery woody fragrance in a refillable bottle.',
        'price' => 165,
        'tags' => ['Man', 'Fragrance', 'Popular'],
        'rating' => '4.8',
        'badge' => 'New Arrival',
        'gallery' => [
            'https://cosmeticsbusiness.com/article-image-alias/spider-man-s-tom-holland-swings-into-prada.jpg',
            'https://perfumeuae.com/wp-content/uploads/2025/08/para-1.jpg',
            'https://tb-static.uber.com/prod/image-proc/processed_images/f2ee7468b6c1bf9e73326764b691e585/b4665c191b34baf3d0e0fa45dfdd3d1d.jpeg',
            'https://profumerialanza.com/cdn/shop/files/prada_paradigme_eau_de_parfum_img1.jpg?v=1769518444&width=900',
            'https://www.prada-beauty.com/on/demandware.static/-/Sites-prada-us-Library/default/dw3cab6365/images/plp/pushes/nav-flyout/NAV-FRAG-PARADIGME.jpg',
        ],
    ],
    'graffiti-classic-city-bag' => [
        'name' => 'Graffiti Classic City Bag',
        'brand' => 'Balenciaga',
        'description' => 'Black and white leather city bag with signature hardware.',
        'price' => 2550,
        'tags' => ['Bag', 'Luxury', 'Popular'],
        'rating' => '4.7',
        'badge' => 'Popular',
        'gallery' => [
            'https://mygemma.com/cdn/shop/articles/mygemma-WPD-Top-Blog-Image-48.png?v=1695913153',
            'https://product-images.therealreal.com/BAL377818_1_enlarged.jpg?auto=webp&width=1400',
            'https://product-images.therealreal.com/BAL377818_2_enlarged.jpg?auto=webp&width=1400',
            'https://product-images.therealreal.com/BAL377818_3_enlarged.jpg?auto=webp&width=1400',
            'https://product-images.therealreal.com/BAL377818_4_enlarged.jpg?auto=webp&width=1400',
            'https://product-images.therealreal.com/BAL377818_5_enlarged.jpg?auto=webp&width=1400',
        ],
    ],
    'polo-blue-parfum' => [
        'name' => 'Polo Blue Parfum',
        'brand' => 'Ralph Lauren',
        'description' => 'Woody fresh parfum with smoky vetiver notes.',
        'price' => 148,
        'tags' => ['Man', 'Fragrance', 'Popular'],
        'rating' => '4.7',
        'badge' => 'Popular',
        'gallery' => [
            'https://i.ytimg.com/vi/kQmjVsaXiKg/maxresdefault.jpg',
            'https://www.ralphlaurenfragrances.com/dw/image/v2/AANG_PRD/on/demandware.static/-/Sites-ralphlauren-master-catalog/default/dw3955e72e/images/pdp/RLFE002/ralph-lauren-fragrances-polo-blue-parfum-pdp-product-carousel.jpg?q=80&sfrm=jpg&sh=1000&sm=cut&sw=1000',
            'https://www.ralphlaurenfragrances.com/dw/image/v2/AANG_PRD/on/demandware.static/-/Sites-ralphlauren-master-catalog/default/dw4c02241b/images/pdp/RLFE002/ralph-lauren-fragrances-polo-blue-parfum-pdp-product-carousel-1.jpg?q=80&sfrm=jpg&sh=1000&sm=cut&sw=1000',
            'https://www.ralphlaurenfragrances.com/dw/image/v2/AANG_PRD/on/demandware.static/-/Sites-ralphlauren-master-catalog/default/dw0dfd8c9d/images/pdp/RLFE002/ralph-lauren-fragrances-polo-blue-eau-de-parfum-pdp-product-carousel-2.jpg?q=80&sfrm=jpg&sh=1000&sm=cut&sw=1000',
            'https://www.ralphlaurenfragrances.com/dw/image/v2/AANG_PRD/on/demandware.static/-/Sites-ralphlauren-master-catalog/default/dwfa581c66/images/pdp/RLFE002/ralph-lauren-fragrances-polo-blue-eau-de-parfum-pdp-product-carousel-3.jpg?q=80&sfrm=jpg&sh=1000&sm=cut&sw=1000',
            'https://www.ralphlaurenfragrances.com/dw/image/v2/AANG_PRD/on/demandware.static/-/Sites-ralphlauren-master-catalog/default/dw4790bbe4/images/pdp/RLFE002/ralph-lauren-fragrances-polo-blue-eau-de-parfum-pdp-product-carousel-4.jpg?q=80&sfrm=jpg&sh=1000&sm=cut&sw=1000',
        ],
    ],
    'classic-fit-mesh-polo' => [
        'name' => 'Classic-Fit Mesh Polo',
        'brand' => 'Polo Ralph Lauren',
        'description' => 'Breathable textured polo with a clean collar.',
        'price' => 110,
        'tags' => ['Man', 'Classic', 'Polo'],
        'rating' => '4.6',
        'badge' => 'Essential',
        'gallery' => [
            'https://i.gadgets360cdn.com/large/Untitled-design161-1766054573857.png',
            'https://images.pexels.com/photos/29499774/pexels-photo-29499774.jpeg',
            'https://images.pexels.com/photos/27334191/pexels-photo-27334191.jpeg',
        ],
    ],
    'air-max-90-off-white' => [
        'name' => 'Air Max 90 Off-White',
        'brand' => 'Nike x Off-White',
        'description' => 'Deconstructed sneaker from The Ten collection.',
        'price' => 160,
        'tags' => ['Man', 'Sneaker', 'Streetwear'],
        'rating' => '4.9',
        'badge' => 'Limited',
        'gallery' => [
            'https://i.ytimg.com/vi/lA_DF1wLEkQ/maxresdefault.jpg',
            'https://storage.googleapis.com/hypeclothinga-media/__sized__/products/NIKE_AIR_MAX_90_OFF-WHITE_OG_AA7293-100_HYPE_CLOTHINGA_LIMITED_EDITION__-thumbnail-1080x1080-70.jpg',
            'https://storage.googleapis.com/hypeclothinga-media/__sized__/products/NIKE_AIR_MAX_90_OFF-WHITE_OG_AA7293-100_HYPE_CLOTHINGA_LIMITED_EDITION___-thumbnail-1080x1080-70.jpg',
            'https://storage.googleapis.com/hypeclothinga-media/__sized__/products/NIKE_AIR_MAX_90_OFF-WHITE_OG_AA7293-100_HYPE_CLOTHINGA_LIMITED_EDITION-thumbnail-1080x1080-70.jpg',
        ],
    ],
    'rebound-v6-low-sneakers' => [
        'name' => 'Rebound V6 Low Sneakers',
        'brand' => 'Puma',
        'description' => 'Low-cut court sneaker with a perforated toe.',
        'price' => 70,
        'tags' => ['Man', 'Sneaker', 'Sport'],
        'rating' => '4.5',
        'badge' => 'Sport',
        'gallery' => [
            'https://t4.ftcdn.net/jpg/05/23/51/15/360_F_523511500_1807EEj4w00yFC6bAVcn82amkEHnBmeg.jpg',
            'https://m.media-amazon.com/images/I/61LPl3y2txL._AC_SY625_.jpg',
            'https://m.media-amazon.com/images/I/61wWqXvKQnL._AC_SY625_.jpg',
            'https://m.media-amazon.com/images/I/71Yc7lCWPpL._AC_SY625_.jpg',
            'https://m.media-amazon.com/images/I/61tAjlzs6IL._AC_SY625_.jpg',
        ],
    ],
    'elite-flr-jacket' => [
        'name' => 'Elite FLR Jacket',
        'brand' => 'Ciele Athletics',
        'description' => 'Ultra-lightweight running shell with reflective details and weather-resistant finish.',
        'price' => 400,
        'tags' => ['Man', 'Jacket', 'Sport'],
        'rating' => '4.6',
        'badge' => 'Lightweight',
        'gallery' => [
            'https://upthereathletics.com/cdn/shop/files/ciele-running-mens-elite-flr-jacket-sable-2.jpg?v=1700521012&width=1100',
            'https://huckberry.imgix.net/spree/products/730690/original/85947_Ciele_Athletics_Lightweight_Performance_Waterproof_Jacket_Sable_01.jpg?auto=format%2C%20compress&crop=top&fit=fill&cs=tinysrgb&ar=4%3A5&fill=solid&fill-color=FFFFFF&ixlib=react-9.8.1',
        ],
    ],
    'mesh-fabric-slingback-pumps' => [
        'name' => 'Mesh Fabric Slingback Pumps',
        'brand' => 'Prada',
        'description' => 'Sheer polyamide mesh slingbacks with leather trim, pointed toe, and iconic screen-printed triangle logo. 75mm varnished heel with leather sole.',
        'price' => 1270,
        'tags' => ['Woman', 'Shoes', 'Luxury'],
        'rating' => '4.8',
        'badge' => 'Luxury',
        'gallery' => [
            'https://thecaistore.com/cdn/shop/files/Frame766-1_2.jpg?v=1773425776',
            'https://m.media-amazon.com/images/I/61wrEtE+ECL._AC_UY1000_.jpg',
            'https://m.media-amazon.com/images/I/51BAUpChJXL._SY625_.jpg',
            'https://m.media-amazon.com/images/I/51g9kYTzU3L._SY625_.jpg'
        ],
    ],
    'no-5-eau-premiere' => [
        'name' => 'No. 5 Eau Premiere',
        'brand' => 'Chanel',
        'description' => 'Light, airy floral version of the classic fragrance.',
        'price' => 176,
        'tags' => ['Woman', 'Fragrance', 'Classic'],
        'rating' => '4.7',
        'badge' => 'Classic',
        'gallery' => [
            'https://static.vecteezy.com/system/resources/previews/013/254/291/non_2x/ternopil-ukraine-september-2-2022-chanel-number-5-eau-premiere-worldwide-famous-french-perfume-bottle-among-other-perfumes-on-shiny-glitter-background-in-yellow-colors-free-photo.JPG',
            'https://scentsware.com/cdn/shop/files/C5F1EEA5-BA44-4F90-A3B9-B0D9C25A53D4.jpg?v=1720211139&width=1445',
            'https://media.karousell.com/media/photos/products/2024/7/6/chanel__dior_perfume_gift_1720258862_dca9f564_progressive.jpg',
        ],
    ],
    'womens-jersey-polo-shirt' => [
        'name' => 'Womens Jersey Polo Shirt',
        'brand' => 'Ralph Lauren',
        'description' => 'Cream cotton-blend polo with classic golf styling.',
        'price' => 98,
        'tags' => ['Woman', 'Polo', 'Golf'],
        'rating' => '4.4',
        'badge' => 'Everyday',
        'gallery' => [
            'https://trendygolfusa.com/cdn/shop/files/LAUNCHES_HERO_7c49c26e-fc63-4418-a7d4-2d4b3d44ece2.jpg?v=1689281730',
        ],
    ],
    'saint-laurent-loulou-bag' => [
        'name' => 'Saint Laurent Loulou Bag',
        'brand' => 'Saint Laurent',
        'description' => 'Quilted leather shoulder bag with iconic YSL logo.',
        'price' => 2850,
        'tags' => ['Woman', 'Bag', 'Luxury'],
        'rating' => '4.8',
        'badge' => 'Luxury',
        'gallery' => [
            'https://www.aglaiamagazine.com/wp-content/uploads/2024/10/saint-laurent-loulou-bag.jpg',
            'https://cdn.salla.sa/RvPxw/204b1f02-6dd2-4df1-81bc-03fbcde78954-1000x940.5756731662-m9Z38QicOfdx9D1en34rD9emuk9cS3fAFbBUeh6N.jpg',
            'https://cdn1.jolicloset.com/imgr/full/2022/06/543147-1/yves-saint-laurent-envelope-large-white-quilted-leather-hand-bag-handbags.jpg',
        ],
    ],
    'cortez-leather-sneaker' => [
        'name' => 'Cortez Leather Sneaker',
        'brand' => 'Nike',
        'description' => 'Low-profile leather runner with retro contrast.',
        'price' => 95,
        'tags' => ['Woman', 'Sneaker', 'Sport'],
        'rating' => '4.6',
        'badge' => 'Retro',
        'gallery' => [
            'https://runnerexpert.com/wp-content/uploads/2024/10/Navrh-bez-nazvu-70-e1730150800370.jpg',
            'https://runnerexpert.com/wp-content/uploads/2024/10/IMG_20241028_090341-min-2048x1532.jpg',
            'https://runnerexpert.com/wp-content/uploads/2024/10/Navrh-bez-nazvu-71-e1730239397236.jpg',
            'https://runnerexpert.com/wp-content/uploads/2024/10/Navrh-bez-nazvu-74-768x575.jpg',
            'https://runnerexpert.com/wp-content/uploads/2024/10/IMG_20241028_090428-600x449.jpg',
        ],
    ],
    'la-femme-intense' => [
        'name' => 'La Femme Intense',
        'brand' => 'Prada',
        'description' => 'Amber floral fragrance with soft vanilla warmth.',
        'price' => 172,
        'tags' => ['Woman', 'Fragrance', 'Luxury'],
        'rating' => '4.7',
        'badge' => 'Luxury',
        'gallery' => [
            'https://i.makeup.be/g/go/goppxwiupxl3.jpg',
            'https://ssbimages.ssbeauty.in/pub/media/catalog/product/images/S25DAVID638625/S25DAVID638625_base.jpg',
        ],
    ],
    'quilted-chain-mini-bag' => [
        'name' => 'Quilted Chain Mini Bag',
        'brand' => 'Gucci',
        'description' => 'Compact quilted bag with polished chain detail.',
        'price' => 1980,
        'tags' => ['Woman', 'Bag', 'Luxury'],
        'rating' => '4.7',
        'badge' => 'Luxury',
        'gallery' => [
            'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?auto=format&fit=crop&w=900&q=70',
            'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?auto=format&fit=crop&w=700&q=80',
        ],
    ],
    'ac-milan-x-off-white-fourth-kit' => [
        'name' => 'AC Milan x Off-White Fourth Kit',
        'brand' => 'Puma x Off-White',
        'description' => 'Limited-edition fourth kit celebrating Black History Month with Pan-African colors and Off-White arrow print.',
        'price' => 130,
        'tags' => ['Man', 'Sport', 'Limited'],
        'rating' => '4.5',
        'badge' => 'Limited',
        'gallery' => [
            'https://wwd.com/wp-content/uploads/2025/02/25SS_PR_TS_Football_ACM-Off-White_Combined_Group_1759_16x9_1920x1080px.jpg?w=800',
            'https://images.puma.com/image/upload/f_auto,q_auto,b_rgb:fafafa,w_600,h_600/global/783001/01/dt01/fnd/PNA/fmt/png/AC-MILAN-x-OFF-WHITE%E2%84%A2-Replica-Men',
            'https://productimages.footy.com/67b105b5415744813e3f1119/0/3840.webp?q=75',
            'https://www.voetbalshop.nl/media/blog/post/2613/20250213-tekst-breedte-PUMA-ACMilan-OffWhite-1.jpg'
        ],
    ],
];

foreach ($productCatalog as $slug => $catalogProduct) {
    $productCatalog[$slug]['slug'] = $slug;
}

$currentSlug = getProductSlug($_GET['product'] ?? 'paradigme-eau-de-parfum');
$product = $productCatalog[$currentSlug] ?? $productCatalog['paradigme-eau-de-parfum'];
$product['category'] = getProductCategory($product);
$product['sizes'] = getProductSizes($product);
$product['active_size'] = getDefaultActiveSize($product, $product['sizes']);

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
    <link rel="stylesheet" href="../assets/css/styles.css?v=89">
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

<?php include 'includes/navbar.php'; ?>
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

    

<?php include 'includes/footer.php'; ?>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/app.js?v=22"></script>
</body>
</html>
