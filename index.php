<?php
$navItems = [
    ['label' => 'Home', 'href' => 'index.php#home', 'active' => true],
    ['label' => 'Gender', 'href' => 'index.php#gender'],
    ['label' => 'Shop', 'href' => 'shop.php'],
    ['label' => 'About', 'href' => 'index.php#about'],
    ['label' => 'New', 'href' => 'index.php#new'],
];

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
        'name' => 'Blazer Mid Premium',
        'brand' => 'Nike',
        'description' => 'Layered high-top sneaker with a vintage edge.',
        'price' => 110,
        'tags' => ['Man', 'Sneaker', 'Popular'],
        'image' => 'https://www.creativeboom.com/upload/articles/34/34fc53c4c1a50ddea6bbb35a25186d2f4bf17262_944.jpg',
    ],
    [
        'name' => 'Paradigme Eau de Parfum',
        'brand' => 'Prada',
        'description' => 'Ambery woody fragrance in a refillable bottle.',
        'price' => 165,
        'tags' => ['Man', 'Fragrance', 'Popular'],
        'image' => 'https://cosmeticsbusiness.com/article-image-alias/spider-man-s-tom-holland-swings-into-prada.jpg',
    ],
    [
        'name' => 'Graffiti Classic City Bag',
        'brand' => 'Balenciaga',
        'description' => 'Black and white leather city bag with signature hardware.',
        'price' => 1595,
        'tags' => ['Bag', 'Luxury', 'Popular'],
        'image' => 'https://mygemma.com/cdn/shop/articles/mygemma-WPD-Top-Blog-Image-48.png?v=1695913153',
    ],
    [
        'name' => 'Polo Blue Parfum',
        'brand' => 'Ralph Lauren',
        'description' => 'Woody fresh parfum with smoky vetiver notes.',
        'price' => 148,
        'tags' => ['Man', 'Fragrance', 'Popular'],
        'image' => 'https://i.ytimg.com/vi/kQmjVsaXiKg/maxresdefault.jpg',
    ],
];

$menProducts = [
    [
        'name' => 'Classic-Fit Mesh Polo',
        'brand' => 'Polo Ralph Lauren',
        'description' => 'Breathable textured polo with a clean collar.',
        'price' => 120,
        'tags' => ['Man', 'Classic', 'Polo'],
        'image' => 'https://i.gadgets360cdn.com/large/Untitled-design161-1766054573857.png',
    ],
    [
        'name' => 'Air Max 90 Off-White',
        'brand' => 'Nike x Off-White',
        'description' => 'Deconstructed sneaker from The Ten collection.',
        'price' => 160,
        'tags' => ['Man', 'Sneaker', 'Streetwear'],
        'image' => 'https://i.ytimg.com/vi/lA_DF1wLEkQ/maxresdefault.jpg',
    ],
    [
        'name' => 'Rebound V6 Low Sneakers',
        'brand' => 'Puma',
        'description' => 'Low-cut court sneaker with a perforated toe.',
        'price' => 70,
        'tags' => ['Man', 'Sneaker', 'Sport'],
        'image' => 'https://t4.ftcdn.net/jpg/05/23/51/15/360_F_523511500_1807EEj4w00yFC6bAVcn82amkEHnBmeg.jpg',
    ],
    [
        'name' => 'Trail Running Jacket',
        'brand' => 'Nike',
        'description' => 'Light shell for cool forest runs.',
        'price' => 125,
        'tags' => ['Man', 'Jacket', 'Sport'],
        'image' => 'https://img.freepik.com/premium-photo/runner-golden-jacket-pauses-check-his-watch-sunlit-forest-path-breath_283470-13385.jpg',
    ],
];

$womenProducts = [
    [
        'name' => 'Mesh Fabric Slingback Pumps',
        'brand' => 'Prada',
        'description' => 'Pointed mesh pumps with triangle logo detail.',
        'price' => 1270,
        'tags' => ['Woman', 'Shoes', 'Luxury'],
        'image' => 'https://i.ebayimg.com/images/g/Q2AAAOSw~GpnWUfI/s-l1200.jpg',
    ],
    [
        'name' => 'No. 5 Eau Premiere',
        'brand' => 'Chanel',
        'description' => 'Light, airy floral version of the classic fragrance.',
        'price' => 185,
        'tags' => ['Woman', 'Fragrance', 'Classic'],
        'image' => 'https://static.vecteezy.com/system/resources/previews/013/254/291/non_2x/ternopil-ukraine-september-2-2022-chanel-number-5-eau-premiere-worldwide-famous-french-perfume-bottle-among-other-perfumes-on-shiny-glitter-background-in-yellow-colors-free-photo.JPG',
    ],
    [
        'name' => 'Womens Jersey Polo Shirt',
        'brand' => 'Ralph Lauren',
        'description' => 'Cream cotton-blend polo with classic golf styling.',
        'price' => 20,
        'tags' => ['Woman', 'Polo', 'Golf'],
        'image' => 'https://trendygolfusa.com/cdn/shop/files/LAUNCHES_HERO_7c49c26e-fc63-4418-a7d4-2d4b3d44ece2.jpg?v=1689281730',
    ],
    [
        'name' => 'GG Marmont Medium Bag',
        'brand' => 'Gucci',
        'description' => 'Black chevron leather shoulder bag with Double G.',
        'price' => 2850,
        'tags' => ['Woman', 'Bag', 'Luxury'],
        'image' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?fm=jpg&q=60&w=3000&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8Z3VjY2klMjBiYWd8ZW58MHx8MHx8fDA%3D',
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
    return strtolower($product['name']) === 'paradigme eau de parfum' ? 'product-detail.php' : '#';
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
    <link rel="stylesheet" href="assets/css/styles.css?v=82">
</head>
<body>
    <header class="site-header" id="home">
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

    <main>
        <section class="hero-section" aria-labelledby="hero-heading">
            <div class="hero-copy">
                <p class="pixel-note">/New Arrival<br>Collection 2026</p>
                <h1 id="hero-heading">Stylish your <span>- Fashion</span></h1>

                <div class="brand-badges" aria-label="Featured brands">
                    <?php foreach ($brandBadges as $badge): ?>
                        <span class="brand-badge" title="<?= htmlspecialchars($badge['name']); ?>">
                            <img src="<?= htmlspecialchars($badge['logo']); ?>" alt="<?= htmlspecialchars($badge['abbr']); ?> logo">
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
                </button>

                <div class="product-panel-content product-panel-content--popular" id="products-popular" data-product-content>
                    <div class="product-grid">
                        <?php foreach ($products as $product): ?>
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
