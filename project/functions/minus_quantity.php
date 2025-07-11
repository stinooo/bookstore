<?php
session_start();
require_once './functies.php';
checkAuthStatus();
ErrorHandelerFun();

if (isset($_POST['productID'])) {
    $productID = $_POST['productID'];
    if (isset($_SESSION['cart'][$productID])) {
        $_SESSION['cart'][$productID]['aantal']--;
    }
    if ($_SESSION['cart'][$productID]['aantal'] == 0) {
        unset($_SESSION['cart'][$productID]);
    }
}
