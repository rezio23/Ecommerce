<?php
$cartCount = getCartCount();
?>
<header class="site-header" id="home">
    <div class="container navbar">
        <a href="index.php#home" class="brand"><?= htmlspecialchars($site['brand']) ?></a>

        <nav class="nav-links">
            <?php foreach ($navLinks as $link): ?>
                <a href="<?= htmlspecialchars($link['href']) ?>"><?= htmlspecialchars($link['label']) ?></a>
            <?php endforeach; ?>
        </nav>

        <div class="nav-icons">
            <a href="index.php#products" aria-label="Search"><i class="fa-solid fa-magnifying-glass"></i></a>
            <a href="account.php" aria-label="Account"><i class="fa-regular fa-user"></i></a>
            <a href="cart.php" aria-label="Shopping bag" class="cart-link">
                <i class="fa-solid fa-bag-shopping"></i>
                <span class="cart-count"><?= $cartCount ?></span>
            </a>
        </div>
    </div>
</header>
