<?php
require 'includes/security.php';
startSecureSession();

$termsSections = [
    [
        'title' => 'Introduction',
        'content' => 'Welcome to The DS. These Terms and Conditions govern your use of our website and the purchase of products from our online store. By accessing or using our website, you agree to be bound by these terms. If you do not agree with any part of these terms, please do not use our services.',
    ],
    [
        'title' => 'Orders and Acceptance',
        'content' => 'All orders placed through our website are subject to acceptance and availability. We reserve the right to refuse or cancel any order for any reason, including limitations on quantities available for purchase, inaccuracies in product descriptions or pricing, or issues identified by our fraud detection systems. Once an order is placed, you will receive an order confirmation email, but this does not constitute acceptance of your order.',
    ],
    [
        'title' => 'Pricing and Payment',
        'content' => 'All prices listed on our website are in US Dollars and are subject to change without notice. We accept payments via KHQR, debit card, and other methods as indicated at checkout. You agree to provide current, complete, and accurate purchase and account information for all purchases made through our store.',
    ],
    [
        'title' => 'Shipping and Delivery',
        'content' => 'We aim to process and ship orders within 1-3 business days. Delivery times vary based on your location and selected shipping method. We are not responsible for delays caused by customs, carrier issues, or other circumstances beyond our control. Risk of loss and title for items purchased pass to you upon delivery to the carrier.',
    ],
    [
        'title' => 'Returns and Refunds',
        'content' => 'We accept returns within 30 days of delivery for items that are unused, unworn, and in their original packaging with all tags attached. Refunds will be processed to the original payment method within 5-10 business days after we receive and inspect the returned item. Shipping costs for returns are the responsibility of the customer unless the return is due to our error.',
    ],
    [
        'title' => 'Product Authenticity',
        'content' => 'The DS guarantees that all products sold are 100% authentic and sourced directly from brand-authorized distributors. Every item undergoes a rigorous verification process before being listed for sale. We do not sell counterfeit or replica products.',
    ],
    [
        'title' => 'Intellectual Property',
        'content' => 'All content on this website, including text, graphics, logos, images, and software, is the property of The DS or its content suppliers and is protected by copyright and other intellectual property laws. You may not reproduce, distribute, or create derivative works from any content without our express written permission.',
    ],
    [
        'title' => 'Limitation of Liability',
        'content' => 'To the fullest extent permitted by law, The DS shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising out of or relating to your use of our website or products. Our total liability shall not exceed the amount you paid for the specific product giving rise to the claim.',
    ],
    [
        'title' => 'Governing Law',
        'content' => 'These Terms and Conditions are governed by and construed in accordance with the laws of the Kingdom of Cambodia. Any disputes arising under these terms shall be subject to the exclusive jurisdiction of the courts of Phnom Penh, Cambodia.',
    ],
    [
        'title' => 'Changes to Terms',
        'content' => 'We reserve the right to update or modify these Terms and Conditions at any time without prior notice. Your continued use of the website following any changes constitutes acceptance of those changes. We encourage you to review these terms periodically.',
    ],
    [
        'title' => 'Contact Us',
        'content' => 'If you have any questions about these Terms and Conditions, please contact us via email at thedaservice@store.com or by phone at +855 112 233.',
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms &amp; Conditions | The DS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Doto:wght@400;600;700;800&family=Krona+One&family=Modak&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css?v=98">
</head>
<body class="terms-page">

<?php
$headerId = 'terms-top';
$searchId = 'header-terms-search';
$bagCount = array_sum(array_column($_SESSION['cart'] ?? [], 'quantity'));
$activeButton = '';
$currentPage = 'shop';
$searchTrigger = 'button';
$rootPath = '../';
$srcPath = '';
?>

<?php include 'includes/navbar.php'; ?>
<main class="terms-main">
    <div class="terms-header-row">
        <nav class="terms-breadcrumb" aria-label="Breadcrumb">
            <a href="../index.php#home">Home</a>
            <span>/</span>
            <span aria-current="page">Terms &amp; Conditions</span>
        </nav>
    </div>

    <section class="terms-hero" aria-labelledby="terms-hero-heading">
        <h1 id="terms-hero-heading">Terms &amp; <span>Conditions</span></h1>
        <p class="terms-hero-lead">Please read these terms carefully before using our website or making a purchase. By using The DS, you agree to these terms.</p>
    </section>

    <section class="terms-content" aria-label="Terms and conditions">
        <div class="terms-card">
            <?php foreach ($termsSections as $index => $section): ?>
                <article class="terms-section" id="terms-section-<?= $index + 1; ?>">
                    <h2><?= htmlspecialchars($section['title']); ?></h2>
                    <p><?= htmlspecialchars($section['content']); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="../assets/js/app.js?v=23"></script>
</body>
</html>
