<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function money(float $amount): string
{
    return '$' . number_format($amount, 0);
}

function renderStars(int $rating): string
{
    $html = '<div class="stars">';

    for ($i = 1; $i <= 5; $i++) {
        $class = $i <= $rating ? 'fa-solid fa-star active' : 'fa-regular fa-star';
        $html .= '<i class="' . $class . '"></i>';
    }

    $html .= '</div>';
    return $html;
}

function getCartCount(): int
{
    return array_sum($_SESSION['cart'] ?? []);
}

function getProductById(array $products, int $id): ?array
{
    foreach ($products as $product) {
        if ((int) $product['id'] === $id) {
            return $product;
        }
    }

    return null;
}

function addToCart(int $productId, int $quantity = 1): void
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $quantity = max(1, $quantity);
    $_SESSION['cart'][$productId] = ($_SESSION['cart'][$productId] ?? 0) + $quantity;
}

function updateCartItem(int $productId, int $quantity): void
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if ($quantity <= 0) {
        unset($_SESSION['cart'][$productId]);
        return;
    }

    $_SESSION['cart'][$productId] = $quantity;
}

function removeFromCart(int $productId): void
{
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

function clearCart(): void
{
    $_SESSION['cart'] = [];
}

function getCartItems(array $products): array
{
    $items = [];
    $cart = $_SESSION['cart'] ?? [];

    foreach ($cart as $productId => $quantity) {
        $product = getProductById($products, (int) $productId);

        if ($product === null) {
            continue;
        }

        $qty = max(1, (int) $quantity);
        $subtotal = (float) $product['price'] * $qty;

        $items[] = [
            'product' => $product,
            'quantity' => $qty,
            'subtotal' => $subtotal,
        ];
    }

    return $items;
}

function getCartSubtotal(array $products): float
{
    $subtotal = 0.0;

    foreach (getCartItems($products) as $item) {
        $subtotal += $item['subtotal'];
    }

    return $subtotal;
}

function renderProductCard(array $product): string
{
    $id = (int) $product['id'];
    $name = htmlspecialchars($product['name']);
    $image = htmlspecialchars($product['image']);
    $category = htmlspecialchars($product['category']);
    $price = money((float) $product['price']);
    $tag = htmlspecialchars($product['tag']);
    $link = 'singleproduct.php?id=' . $id;

    return '
        <article class="product-card">
            <a class="product-thumb-link" href="' . $link . '">
                <div class="product-image-wrap">
                    <img src="' . $image . '" alt="' . $name . '" class="product-image">
                </div>
            </a>
            <div class="product-meta-top">
                <span class="product-category">' . $category . '</span>
                <span class="product-tag">' . $tag . '</span>
            </div>
            <a class="product-title-link" href="' . $link . '">
                <h3 class="product-name">' . $name . '</h3>
            </a>
            ' . renderStars((int) $product['rating']) . '
            <p class="product-price">' . $price . '</p>
            <div class="product-actions">
                <a class="details-btn" href="' . $link . '">View Details</a>
                <a class="buy-btn" href="index.php?add=' . $id . '#products">Add To Cart</a>
            </div>
        </article>
    ';
}
