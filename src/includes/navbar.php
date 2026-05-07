<?php
$currentPage = $currentPage ?? '';
$headerId = $headerId ?? '';
$searchId = $searchId ?? 'header-product-search';
$bagCount = $bagCount ?? 0;
$activeButton = $activeButton ?? '';
$searchTrigger = ($searchTrigger ?? 'button') === 'button' ? 'button' : 'link';

$rootPath = $rootPath ?? '';
$srcPath = $srcPath ?? '';

$navItems = [
    ['label' => 'Home', 'href' => $rootPath . 'index.php#home_text', 'active' => $currentPage === 'home'],
    ['label' => 'Shop', 'href' => $srcPath . 'shop.php', 'active' => $currentPage === 'shop'],
    ['label' => 'About', 'href' => $rootPath . 'index.php#about', 'active' => $currentPage === 'about'],
    ['label' => 'New', 'href' => $rootPath . 'index.php#new', 'active' => $currentPage === 'new'],
];

$accountLink = $activeButton === 'profile' ? ($srcPath . 'profile.php') : ($srcPath . 'login.php');
$accountLabel = $activeButton === 'profile' ? 'Account profile' : 'Account login';
$accountTitle = $activeButton === 'profile' ? 'Account' : 'Account';
?>
<header class="site-header" id="<?= htmlspecialchars($headerId); ?>">
    <a class="brand-mark" href="<?= $rootPath; ?>index.php#home" aria-label="The DS home">the DS</a>

    <nav class="site-nav" aria-label="Primary navigation">
        <?php foreach ($navItems as $item): ?>
            <?php $navClass = !empty($item['active']) ? 'is-active' : ''; ?>
            <?php if ($navClass): ?>
                <a class="<?= $navClass; ?>" href="<?= htmlspecialchars($item['href']); ?>">
            <?php else: ?>
                <a href="<?= htmlspecialchars($item['href']); ?>">
            <?php endif; ?>
                <?= htmlspecialchars($item['label']); ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="header-actions" aria-label="Store actions">
        <div class="header-search" data-header-search>
            <input class="header-search-input" id="<?= htmlspecialchars($searchId); ?>" data-product-search type="search" placeholder="Search products..." aria-label="Search products" autocomplete="off" tabindex="-1" aria-hidden="true">
            <?php if ($searchTrigger === 'button'): ?>
                <button class="icon-button search-trigger" type="button" aria-label="Open search" aria-controls="<?= htmlspecialchars($searchId); ?>" aria-expanded="false" title="Search">
                    <i data-lucide="search"></i>
                </button>
            <?php else: ?>
                <a class="icon-button search-trigger" href="<?= $srcPath; ?>shop.php" aria-label="Search products" title="Search">
                    <i data-lucide="search"></i>
                </a>
            <?php endif; ?>
        </div>
        <a class="icon-button bag-button <?= $activeButton === 'bag' ? 'is-active' : ''; ?>" href="<?= $srcPath; ?>cart.php" aria-label="Shopping bag" title="Bag">
            <i data-lucide="shopping-bag"></i>
            <span class="bag-count" aria-live="polite"><?= (int) $bagCount; ?></span>
        </a>
        <a class="icon-button <?= $activeButton === 'account' || $activeButton === 'profile' ? 'is-active' : ''; ?>" href="<?= $accountLink; ?>" aria-label="<?= $accountLabel; ?>" title="<?= $accountTitle; ?>">
            <i data-lucide="user-round"></i>
        </a>
        <button class="icon-button nav-toggle" type="button" aria-label="Open menu" aria-expanded="false" title="Menu">
            <i data-lucide="menu" class="nav-toggle-open"></i>
            <i data-lucide="x" class="nav-toggle-close"></i>
        </button>
    </div>
</header>
