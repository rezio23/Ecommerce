<?php
$navItems = [
    ['label' => 'Home', 'href' => 'index.php#home'],
    ['label' => 'Gender', 'href' => 'index.php#gender'],
    ['label' => 'Shop', 'href' => 'shop.php', 'active' => true],
    ['label' => 'About', 'href' => 'index.php#about'],
    ['label' => 'New', 'href' => 'index.php#new'],
];

$cartItems = [
    [
        'name' => 'Paradigme Eau de Parfum',
        'brand' => 'Prada',
        'size' => '150ml',
        'price' => 19.99,
        'quantity' => 1,
        'image' => 'https://cosmeticsbusiness.com/article-image-alias/spider-man-s-tom-holland-swings-into-prada.jpg',
    ],
    [
        'name' => 'Paradigme Eau de Parfum',
        'brand' => 'Prada',
        'size' => '150ml',
        'price' => 19.99,
        'quantity' => 1,
        'image' => 'https://perfumeuae.com/wp-content/uploads/2025/08/para-1.jpg',
    ],
];

$subtotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cartItems));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart | The DS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Doto:wght@400;600;700;800&family=Krona+One&family=Modak&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css?v=93">
</head>
<body class="cart-page">
    <header class="site-header" id="cart-top">
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
                <input class="header-search-input" id="header-cart-search" data-product-search type="search" placeholder="Search products..." aria-label="Search products" autocomplete="off" tabindex="-1" aria-hidden="true">
                <button class="icon-button search-trigger" type="button" aria-label="Open search" aria-controls="header-cart-search" aria-expanded="false" title="Search">
                    <i data-lucide="search"></i>
                </button>
            </div>
            <a class="icon-button bag-button is-active" href="cart.php" aria-label="Shopping bag" title="Bag">
                <i data-lucide="shopping-bag"></i>
                <span class="bag-count" aria-live="polite"><?= count($cartItems); ?></span>
            </a>
            <a class="icon-button" href="profile.php" aria-label="Account profile" title="Account">
                <i data-lucide="user-round"></i>
            </a>
        </div>
    </header>

    <main class="cart-main">
        <nav class="cart-breadcrumb" aria-label="Breadcrumb">
            <a href="index.php#home">Home</a>
            <span>/</span>
            <a href="shop.php">Shop</a>
            <span>/</span>
            <span aria-current="page">Cart</span>
        </nav>

        <div class="cart-header-row">
            <h1>Find out your preferring products</h1>
            <a href="#" class="cart-help-link">Help Center</a>
        </div>

        <div class="cart-table-wrap">
            <div class="cart-table-header">
                <span>Product</span>
                <span>Unit Price</span>
                <span>Quantity</span>
                <span>Total</span>
            </div>

            <?php foreach ($cartItems as $index => $item): ?>
                <div class="cart-table-row <?= $index % 2 === 0 ? 'cart-table-row--even' : 'cart-table-row--odd'; ?>">
                    <div class="cart-product-cell">
                        <img src="<?= htmlspecialchars($item['image']); ?>" alt="<?= htmlspecialchars($item['name']); ?>">
                        <div class="cart-product-meta">
                            <strong><?= htmlspecialchars($item['name']); ?></strong>
                            <span>Size: <?= htmlspecialchars($item['size']); ?></span>
                        </div>
                    </div>
                    <div class="cart-price-cell">$ <?= number_format($item['price'], 2); ?></div>
                    <div class="cart-quantity-cell">
                        <div class="cart-qty-control">
                            <button type="button" class="cart-qty-btn" data-cart-qty="-1" aria-label="Decrease quantity">
                                <i data-lucide="minus" aria-hidden="true"></i>
                            </button>
                            <span class="cart-qty-value"><?= (int) $item['quantity']; ?></span>
                            <button type="button" class="cart-qty-btn" data-cart-qty="1" aria-label="Increase quantity">
                                <i data-lucide="plus" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <div class="cart-total-cell">$ <?= number_format($item['price'] * $item['quantity'], 2); ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="cart-summary">
            <hr class="cart-summary-line">
            <div class="cart-subtotal">
                <span>Sub Total:</span>
                <strong>$ <?= number_format($subtotal, 2); ?></strong>
            </div>
            <a href="#" class="cart-checkout-btn">Go to Checkout</a>
        </div>
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
    <script src="assets/js/app.js?v=23"></script>
    <script>
        document.querySelectorAll('[data-cart-qty]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const row = btn.closest('.cart-table-row');
                const valueEl = row?.querySelector('.cart-qty-value');
                if (!valueEl) return;
                let qty = parseInt(valueEl.textContent, 10) || 0;
                const delta = parseInt(btn.dataset.cartQty, 10) || 0;
                qty = Math.max(1, qty + delta);
                valueEl.textContent = String(qty);
            });
        });
    </script>
</body>
</html>
