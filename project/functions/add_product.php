<?php
session_start();
require_once 'functies.php';
ErrorHandelerFun();


if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "Unauthorized access.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['bookname'];
    $product_price = $_POST['price'];
    $product_description = $_POST['description'];
    $product_author = $_POST['author'];
    $product_show_on_web = $_POST['show_on_web'];
    $product_sale = $_POST['sale'];
    $product_image = $_POST['imageplace'];
    $product_Feautures = $_POST['Featured'];

    if (empty($product_name) || empty($product_price) || empty($product_author) || empty($product_description)) {
        echo "All fields are required.";
        exit();
    }

    $link = startlink();
    $query = "INSERT INTO products (productname, author, price, description, imageplace, madeby, show_on_web, sale, Featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "ssissiiii", $product_name, $product_author, $product_price, $product_description, $product_image, $_SESSION['user_id'], $product_show_on_web, $product_sale, $product_Feautures);
    mysqli_stmt_execute($stmt);

    $products = getall('products');
    foreach ($products as $product) {
        $users_result = getone('users', $product['madeby']);
        $user = mysqli_fetch_assoc($users_result);
        echo "<tr>";
        echo "<td>" . $product['id'] . "</td>";
        echo "<td>" . $product['productname'] . "</td>";
        echo "<td>" . $product['price'] . "</td>";
        echo "<td>" . $product['sale'] . "</td>";
        echo "<td>" . $product['author'] . "</td>";
        echo "<td>" . $user['username'] . "</td>";
        echo "<td>" . $product['imageplace'] . "</td>";
        echo "<td>" . ($product['show_on_web'] == "1" ? "true" : "false") . "</td>";
        echo "<td><button class='button2' onclick=\"window.location.href='product.php?id=" . $product['id'] . "'\">Go to product Page</button></td>";
        echo "</tr>";
    }

    endlink($link);
    exit();
}
?>