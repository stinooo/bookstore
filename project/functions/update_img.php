<?php
session_start();
require_once './functies.php';
checkAuthStatus();
ErrorHandelerFun();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: shop.php");
    exit();
}

if (isset($_POST['productID'])) {
    $productID = $_POST['productID'];
    $res = getone('products', $productID);
    $product = mysqli_fetch_assoc($res);
    echo "<div id='img'>";
    echo "<img class='imgproduct' id='imgproduct' src='./photos/" . $product['imageplace'] . "' alt='" . $product['id'] . "'>";
    echo "</div>";
}
