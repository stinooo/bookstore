<?php
session_start();
require_once './functies.php';
checkAuthStatus();
ErrorHandelerFun();
$link = startlink();
if (isset($_POST['productID'])) {
    $productID = $_POST['productID'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    if (isset($_SESSION['cart'][$productID])) {
        $_SESSION['cart'][$productID]['aantal']++;
    } else {
        $query = "SELECT * FROM products WHERE id = $productID";
        $res = mysqli_query($link, $query);
        $row = mysqli_fetch_assoc($res);
        $_SESSION['cart'][$productID] = array(
            'pic' => './photos/' . $row['imageplace'],
            'name' => $row['productname'],
            'id' => $row['id'],
            'price' => $row['price'],
            'sale' => $row['sale'],
            'aantal' => 1,
        );
    }
    endlink($link);
}
