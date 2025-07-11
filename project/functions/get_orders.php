<?php
session_start();
require_once './functies.php';
checkAuthStatus();
ErrorHandelerFun();

$user_id = $_SESSION['user_id'];
$orders = getOrders($user_id);

while ($order = mysqli_fetch_assoc($orders)) {
    echo "<div class='order'>";
    echo "<a href='./order.php?id=" . $order['id'] . "'>Order ID: " . $order['id'] . "</a>";
    echo "<p>Order Date: " . $order['made@'] . "</p>";
    echo "</div>";
}
?>