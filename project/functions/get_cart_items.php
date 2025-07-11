<?php
session_start();
require_once './functies.php';
checkAuthStatus();
ErrorHandelerFun();

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {

    foreach ($_SESSION['cart'] as $product) {
        $price = $product['price'] - ($product['price'] * ($product['sale'] / 100));

            
        echo '<div class="cart-item">';
        echo '<div class="cart-item-left">';
        echo '<div class="cart-item-image">';
        echo '<img class="cart-img" src="' . $product['pic'] . '" alt="product">';
        echo '</div>';

        echo '<div class="cart-item-info">';
        echo '<div class="cart-item-name">' . $product['name'] . '</div>';
        echo '<div class="cart-item-price">€' . number_format($price, 2) . '</div>';
        echo '</div>';
        echo '</div>';

        echo '<div class="cart-item-right">';
        echo '<button class="quantity-btn minus-btn" value="' . $product['id'] . '">';
        echo '<i class="fas fa-minus"></i>';
        echo '</button>';
        echo '<div class="cart-item-quantity">x' . $product['aantal'] . '</div>';
        echo '<button class="quantity-btn plus-btn" value="' . $product['id'] . '">';
        echo '<i class="fas fa-plus"></i>';
        echo '</button>';
        echo '<button class="remove-from-cart" value="' . $product['id'] . '">';
        echo '<i class="fas fa-trash"></i>';
        echo '</button>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo '<p class="cart-empty">No items in cart</p>';
}
