<?php
session_start();
require_once './functies.php';
ErrorHandelerFun();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("Error: Cart is empty or not set");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $postal_code = $_POST['postal_code'];
    $payment_method = $_POST['payment_method'];
    $userID = $_SESSION['user_id'];
    $total = $_POST['total'];

    $link = startlink();
    $query = "INSERT INTO orders (user_id, total, name, phone, address, city, country, postal_code, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $res = $link->prepare($query);
    $res->bind_param("idsssssss", $userID, $total, $name, $phone, $address, $city, $country, $postal_code, $payment_method);
    $res->execute();

    $query2 = "SELECT id FROM orders WHERE user_id = $userID ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($link, $query2);
    $result = mysqli_fetch_assoc($result);
    $order_id = $result['id'];

    foreach ($_SESSION['cart'] as $products):
        $price = $products['price'] - ($products['price'] * ($products['sale'] / 100));
        $query = "INSERT INTO order_items (order_id, product_id, amount, price , sale) VALUES (?, ?, ?, ?, ?)";
        $res = $link->prepare($query);
        $res->bind_param("iiidd", $order_id, $products['id'], $products['aantal'], $price, $products['sale']);
        $res->execute();
    endforeach;

    unset($_SESSION['cart']);
    header("Location: ../success.php");
    endlink($link);
}
