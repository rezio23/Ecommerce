<?php
$rootPath = $rootPath ?? '';
$srcPath = $srcPath ?? '';
?>
<footer class="site-footer">
    <section class="footer-brand" aria-label="Store footer">
        <a class="brand-mark brand-mark--footer" href="<?= $rootPath; ?>index.php#home">the DS</a>
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
            <a href="<?= $rootPath; ?>index.php#about">About</a>
            <a href="<?= $rootPath; ?>index.php#new">Products</a>
            <a href="<?= $rootPath; ?>index.php#gender">Categories</a>
        </nav>
        <nav class="footer-group footer-links" aria-label="Footer shop links">
            <h2>Shop</h2>
            <a href="<?= $srcPath; ?>shop.php?category=clothes#shop-grid">Clothes</a>
            <a href="<?= $srcPath; ?>shop.php?category=perfumes#shop-grid">Perfumes</a>
            <a href="<?= $srcPath; ?>shop.php?category=accessories#shop-grid">Accessories</a>
            <a href="<?= $srcPath; ?>shop.php?category=bags#shop-grid">Bag</a>
            <a href="<?= $srcPath; ?>shop.php?category=sneakers#shop-grid">Sneakers</a>
        </nav>
        <nav class="footer-group footer-links footer-links--brands" aria-label="Footer brand links">
            <h2>Brand</h2>
            <div>
                <a href="<?= $srcPath; ?>shop.php#shop-grid">Polo</a>
                <a href="<?= $srcPath; ?>shop.php#shop-grid">Balenciaga</a>
                <a href="<?= $srcPath; ?>shop.php#shop-grid">Prada</a>
                <a href="<?= $srcPath; ?>shop.php#shop-grid">Puma</a>
                <a href="<?= $srcPath; ?>shop.php#shop-grid">Gucci</a>
                <a href="<?= $srcPath; ?>shop.php#shop-grid">Nike</a>
            </div>
        </nav>
        <nav class="footer-group footer-links" aria-label="Footer legal links">
            <h2>Legal</h2>
            <a href="<?= $srcPath; ?>terms.php">Terms &amp; Conditions</a>
        </nav>
    </div>
</footer>
