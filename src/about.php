<?php
$aboutStats = [
    ['value' => '120+', 'label' => 'Premium Brands'],
    ['value' => '50K', 'label' => 'Happy Customers'],
    ['value' => '15', 'label' => 'Countries Served'],
    ['value' => '99%', 'label' => 'Authentic Products'],
];

$aboutValues = [
    [
        'icon' => 'shield-check',
        'title' => 'Authenticity Guaranteed',
        'text' => 'Every product is sourced directly from brand-authorized distributors. We never compromise on authenticity.',
    ],
    [
        'icon' => 'truck',
        'title' => 'Fast Global Shipping',
        'text' => 'From Phnom Penh to Paris, our logistics network ensures your order arrives swiftly and safely.',
    ],
    [
        'icon' => 'headphones',
        'title' => 'Dedicated Support',
        'text' => 'Our team is here to help with sizing, styling advice, or any questions about your order.',
    ],
    [
        'icon' => 'refresh-ccw',
        'title' => 'Easy Returns',
        'text' => 'Not the perfect fit? Return within 30 days for a full refund or exchange — no questions asked.',
    ],
];

$aboutTeam = [
    [
        'name' => 'Vichhean Sombath',
        'role' => 'Founder & CEO',
        'image' => 'https://i1.sndcdn.com/avatars-tDQKBExQks6cE0zh-HO3N7Q-t240x240.jpg',
    ],
    [
        'name' => 'Creative Director',
        'role' => 'Head of Curation',
        'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80',
    ],
    [
        'name' => 'Operations Lead',
        'role' => 'Logistics & Fulfillment',
        'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About | The DS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Doto:wght@400;600;700;800&family=Krona+One&family=Modak&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css?v=97">
</head>
<body class="about-page">

<?php
$headerId = 'about-top';
$searchId = 'header-about-search';
$bagCount = 0;
$activeButton = '';
$currentPage = 'about';
$searchTrigger = 'button';
$rootPath = '../';
$srcPath = '';
?>

<?php include 'includes/navbar.php'; ?>
<main class="about-main">
    <div class="about-header-row">
        <nav class="about-breadcrumb" aria-label="Breadcrumb">
            <a href="../index.php#home">Home</a>
            <span>/</span>
            <span aria-current="page">About</span>
        </nav>
    </div>

    <section class="about-hero" aria-labelledby="about-hero-heading">
        <div class="about-hero-copy">
            <p class="pixel-note">/Our Story</p>
            <h1 id="about-hero-heading">The DS —<br><span>Curated Luxury</span></h1>
            <p class="about-hero-lead">Born from a passion for premium fashion and authentic streetwear, The DS brings the world's most coveted brands to your doorstep.</p>
            <a href="shop.php" class="about-hero-cta">
                Explore the Collection
                <i data-lucide="arrow-right" aria-hidden="true"></i>
            </a>
        </div>
        <figure class="about-hero-model">
            <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=800&q=80" alt="Luxury fashion boutique interior">
        </figure>
    </section>

    <section class="about-story" aria-labelledby="about-story-heading">
        <div class="about-story-card">
            <h2 id="about-story-heading">Our Mission</h2>
            <p>We believe luxury should be accessible, authentic, and effortless. The DS was founded to bridge the gap between global premium brands and style-conscious individuals who demand quality without compromise.</p>
            <p>From limited-edition sneakers to timeless fragrances, every piece in our collection is hand-selected and verified for authenticity. We partner directly with authorized distributors to ensure that what you receive is exactly what the brand intended.</p>
        </div>
    </section>

    <section class="about-stats" aria-label="Key metrics">
        <?php foreach ($aboutStats as $stat): ?>
            <div class="about-stat">
                <strong><?= htmlspecialchars($stat['value']); ?></strong>
                <span><?= htmlspecialchars($stat['label']); ?></span>
            </div>
        <?php endforeach; ?>
    </section>

    <section class="about-values" aria-labelledby="about-values-heading">
        <h2 id="about-values-heading">Why Shop With Us</h2>
        <div class="about-values-grid">
            <?php foreach ($aboutValues as $value): ?>
                <article class="about-value-card">
                    <i data-lucide="<?= htmlspecialchars($value['icon']); ?>" aria-hidden="true"></i>
                    <h3><?= htmlspecialchars($value['title']); ?></h3>
                    <p><?= htmlspecialchars($value['text']); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="about-team" aria-labelledby="about-team-heading">
        <h2 id="about-team-heading">Meet the Team</h2>
        <div class="about-team-grid">
            <?php foreach ($aboutTeam as $member): ?>
                <article class="about-team-card">
                    <img src="<?= htmlspecialchars($member['image']); ?>" alt="<?= htmlspecialchars($member['name']); ?>">
                    <h3><?= htmlspecialchars($member['name']); ?></h3>
                    <p><?= htmlspecialchars($member['role']); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="about-cta-band" aria-label="Call to action">
        <p>Ready to elevate your wardrobe?</p>
        <a href="shop.php">Shop Now</a>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="../assets/js/app.js?v=23"></script>
</body>
</html>
