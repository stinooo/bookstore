<?php
session_start();
require_once './functies.php';
checkAuthStatus();
ErrorHandelerFun();

if (isset($_POST['productID'])) {
    $productID = $_POST['productID'];
    if (isset($_SESSION['cart'][$productID])) {
        unset($_SESSION['cart'][$productID]);
    }
}
