<?php
session_start();
require_once './functies.php';
checkAuthStatus();
ErrorHandelerFun();
$total = 0;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product) {
        $price = $product['price'] - ($product['price'] * ($product['sale'] / 100));
        $total += $price * $product['aantal'];
    }
    $total = number_format($total, 2);

    echo "$total";
} else {
    echo "0.00";
}
