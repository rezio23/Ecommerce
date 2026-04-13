<footer class="site-footer" id="footer">
    <div class="container footer-grid">
        <div class="footer-column about-column">
            <a href="index.php#home" class="footer-logo"><?= htmlspecialchars($site['brand']) ?></a>
            <p>
                We provide the best products for the most affordable prices.
                This PHP version now includes a product page, cart page, and total summary.
            </p>
        </div>

        <div class="footer-column" id="featured">
            <h4>Featured</h4>
            <ul>
                <?php foreach ($featuredLinks as $item): ?>
                    <li><a href="index.php#products"><?= htmlspecialchars($item) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="footer-column">
            <h4>Contact Us</h4>
            <p><strong>ADDRESS</strong><br><?= htmlspecialchars($site['contact_address']) ?></p>
            <p><strong>PHONE</strong><br><?= htmlspecialchars($site['contact_phone']) ?></p>
            <p><strong>EMAIL</strong><br><?= htmlspecialchars($site['contact_email']) ?></p>
        </div>

        <div class="footer-column instagram-column">
            <h4>Instagram</h4>
            <div class="instagram-grid">
                <?php foreach ($instagramImages as $image): ?>
                    <img src="<?= htmlspecialchars($image) ?>" alt="Product preview image">
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="container footer-bottom">
        <p><?= htmlspecialchars($site['copyright']) ?></p>
        <div class="social-links">
            <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
            <a href="#" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
        </div>
    </div>
</footer>
