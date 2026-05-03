<?php
$navItems = [
    ['label' => 'Home', 'href' => 'index.php#home'],
    ['label' => 'Gender', 'href' => 'index.php#gender'],
    ['label' => 'Shop', 'href' => 'shop.php', 'active' => true],
    ['label' => 'About', 'href' => 'index.php#about'],
    ['label' => 'New', 'href' => 'index.php#new'],
];

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
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto%2Cq_auto%3Aeco%2Cu_9ddf04c7-2a9a-4d76-add1-d15af8f0263d%2Cc_scale%2Cfl_relative%2Cw_1.0%2Ch_1.0%2Cfl_layer_apply/8af72051-937c-4b7a-9676-86638e6f4faf/BLAZER%2BMID%2B%2777%2BPRM.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto%2Cq_auto%3Aeco%2Cu_9ddf04c7-2a9a-4d76-add1-d15af8f0263d%2Cc_scale%2Cfl_relative%2Cw_1.0%2Ch_1.0%2Cfl_layer_apply/bfd07f21-0c77-4d75-8e2b-80f572d0c7d8/BLAZER%2BMID%2B%2777%2BPRM.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto%2Cq_auto%3Aeco%2Cu_9ddf04c7-2a9a-4d76-add1-d15af8f0263d%2Cc_scale%2Cfl_relative%2Cw_1.0%2Ch_1.0%2Cfl_layer_apply/05bdbfba-a04e-4ef5-94c5-43c195b6f7f7/BLAZER%2BMID%2B%2777%2BPRM.png',
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
        'price' => 1595,
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
        'price' => 120,
        'tags' => ['Man', 'Classic', 'Polo'],
        'rating' => '4.6',
        'badge' => 'Essential',
        'gallery' => [
            'https://i.gadgets360cdn.com/large/Untitled-design161-1766054573857.png',
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
            'https://image.goat.com/transform/v1/attachments/product_template_pictures/images/079/296/946/original/466439_01.png.png?action=crop&width=1200',
            'https://image.goat.com/transform/v1/attachments/product_template_pictures/images/079/296/949/original/466439_02.png.png?action=crop&width=1200',
            'https://image.goat.com/transform/v1/attachments/product_template_pictures/images/079/296/948/original/466439_03.png.png?action=crop&width=1200',
            'https://image.goat.com/transform/v1/attachments/product_template_pictures/images/079/296/950/original/466439_04.png.png?action=crop&width=1200',
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
            'https://images.puma.com/image/upload/f_auto,q_auto,w_1200,b_rgb:FAFAFA/global/392328/01/sv01/fnd/MEX/fmt/png',
            'https://images.puma.com/image/upload/f_auto,q_auto,w_1200,b_rgb:FAFAFA/global/392328/01/mod01/fnd/MEX/fmt/png',
            'https://images.puma.com/image/upload/f_auto,q_auto,w_1200,b_rgb:FAFAFA/global/392328/01/mod02/fnd/MEX/fmt/png',
            'https://images.puma.com/image/upload/f_auto,q_auto,w_1200,b_rgb:FAFAFA/global/392328/01/fnd/MEX/fmt/png',
        ],
    ],
    'trail-running-jacket' => [
        'name' => 'Trail Running Jacket',
        'brand' => 'Nike',
        'description' => 'Light shell for cool forest runs.',
        'price' => 125,
        'tags' => ['Man', 'Jacket', 'Sport'],
        'rating' => '4.6',
        'badge' => 'Lightweight',
        'gallery' => [
            'https://img.freepik.com/premium-photo/runner-golden-jacket-pauses-check-his-watch-sunlit-forest-path-breath_283470-13385.jpg',
        ],
    ],
    'mesh-fabric-slingback-pumps' => [
        'name' => 'Mesh Fabric Slingback Pumps',
        'brand' => 'Prada',
        'description' => 'Pointed mesh pumps with triangle logo detail.',
        'price' => 1270,
        'tags' => ['Woman', 'Shoes', 'Luxury'],
        'rating' => '4.8',
        'badge' => 'Luxury',
        'gallery' => [
            'https://i.ebayimg.com/images/g/Q2AAAOSw~GpnWUfI/s-l1200.jpg',
        ],
    ],
    'no-5-eau-premiere' => [
        'name' => 'No. 5 Eau Premiere',
        'brand' => 'Chanel',
        'description' => 'Light, airy floral version of the classic fragrance.',
        'price' => 185,
        'tags' => ['Woman', 'Fragrance', 'Classic'],
        'rating' => '4.7',
        'badge' => 'Classic',
        'gallery' => [
            'https://static.vecteezy.com/system/resources/previews/013/254/291/non_2x/ternopil-ukraine-september-2-2022-chanel-number-5-eau-premiere-worldwide-famous-french-perfume-bottle-among-other-perfumes-on-shiny-glitter-background-in-yellow-colors-free-photo.JPG',
            'https://www.allbeauty.com/images?url=https://static.thcdn.com/productimg/original/12271391-1835265763900530.jpg&format=webp&auto=avif&width=1000&height=1000&fit=cover',
        ],
    ],
    'womens-jersey-polo-shirt' => [
        'name' => 'Womens Jersey Polo Shirt',
        'brand' => 'Ralph Lauren',
        'description' => 'Cream cotton-blend polo with classic golf styling.',
        'price' => 20,
        'tags' => ['Woman', 'Polo', 'Golf'],
        'rating' => '4.4',
        'badge' => 'Everyday',
        'gallery' => [
            'https://trendygolfusa.com/cdn/shop/files/LAUNCHES_HERO_7c49c26e-fc63-4418-a7d4-2d4b3d44ece2.jpg?v=1689281730',
        ],
    ],
    'gg-marmont-medium-bag' => [
        'name' => 'GG Marmont Medium Bag',
        'brand' => 'Gucci',
        'description' => 'Black chevron leather shoulder bag with Double G.',
        'price' => 2850,
        'tags' => ['Woman', 'Bag', 'Luxury'],
        'rating' => '4.8',
        'badge' => 'Luxury',
        'gallery' => [
            'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?fm=jpg&q=60&w=3000&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8Z3VjY2klMjBiYWd8ZW58MHx8MHx8fDA%3D',
            'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?fm=jpg&q=70&w=1800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?fm=jpg&q=80&w=1200&auto=format&fit=crop',
        ],
    ],
    'cortez-leather-sneaker' => [
        'name' => 'Cortez Leather Sneaker',
        'brand' => 'Nike',
        'description' => 'Low-profile leather runner with retro contrast.',
        'price' => 90,
        'tags' => ['Woman', 'Sneaker', 'Sport'],
        'rating' => '4.6',
        'badge' => 'Retro',
        'gallery' => [
            'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=1200&q=80',
            'https://static.nike.com/a/images/t_PDP_1728_v1/u_9ddf04c7-2a9a-4d76-add1-d15af8f0263d%2Cc_scale%2Cfl_relative%2Cw_1.0%2Ch_1.0%2Cfl_layer_apply/734a4eb2-bcb0-467e-b43f-76c471be16e5/W%2BNIKE%2BCORTEZ.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/u_9ddf04c7-2a9a-4d76-add1-d15af8f0263d%2Cc_scale%2Cfl_relative%2Cw_1.0%2Ch_1.0%2Cfl_layer_apply/d82a66ff-38f2-45b7-b22a-e9080adca623/W%2BNIKE%2BCORTEZ.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/u_9ddf04c7-2a9a-4d76-add1-d15af8f0263d%2Cc_scale%2Cfl_relative%2Cw_1.0%2Ch_1.0%2Cfl_layer_apply/2a766a36-07ba-49e8-921b-3f259960744a/W%2BNIKE%2BCORTEZ.png',
            'https://static.nike.com/a/images/t_PDP_1728_v1/u_9ddf04c7-2a9a-4d76-add1-d15af8f0263d%2Cc_scale%2Cfl_relative%2Cw_1.0%2Ch_1.0%2Cfl_layer_apply/909b9a87-9c5a-4617-85cc-e3eec53fbc18/W%2BNIKE%2BCORTEZ.png',
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
            'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?auto=format&fit=crop&w=1200&q=80',
            'https://www.allbeauty.com/images?url=https://static.thcdn.com/productimg/original/12271391-1835265763900530.jpg&format=webp&auto=avif&width=1000&height=1000&fit=cover',
            'https://i.makeup.be/g/go/goppxwiupxl3.jpg',
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
    'performance-half-zip-jacket' => [
        'name' => 'Performance Half-Zip Jacket',
        'brand' => 'Puma',
        'description' => 'Light training layer for warmups and city runs.',
        'price' => 95,
        'tags' => ['Man', 'Jacket', 'Sport'],
        'rating' => '4.5',
        'badge' => 'Training',
        'gallery' => [
            'https://images.unsplash.com/photo-1523381294911-8d3cead13475?auto=format&fit=crop&w=1200&q=80',
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
    <link rel="stylesheet" href="assets/css/styles.css?v=89">
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
            <a class="icon-button bag-button" href="cart.php" aria-label="Shopping bag" title="Bag">
                <i data-lucide="shopping-bag"></i>
                <span class="bag-count" aria-live="polite">0</span>
            </a>
            <a class="icon-button" href="profile.php" aria-label="Account profile" title="Account">
                <i data-lucide="user-round"></i>
            </a>
        </div>
    </header>

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
    <script src="assets/js/app.js?v=22"></script>
</body>
</html>
