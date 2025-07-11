<?php
// filepath: /c:/xampp/htdocs/labo/project/functions/update_product.php
session_start();
require_once 'functies.php';
ErrorHandelerFun();

// Check if the user is logged in and has the 'admin' role
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "Unauthorized access.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['bookID'])) {
        echo "No product ID provided.";
        exit();
    }

    $product_id = $_POST['bookID'];
    $product_name = isset($_POST['bookname']) && $_POST['bookname'] !== '' ? $_POST['bookname'] : null;
    $product_price = isset($_POST['price']) && $_POST['price'] !== '' ? $_POST['price'] : null;
    $product_author = isset($_POST['author']) && $_POST['author'] !== '' ? $_POST['author'] : null;
    $product_show_on_web = isset($_POST['show_on_web']) ? $_POST['show_on_web'] : null;
    $product_sale = isset($_POST['sale']) && $_POST['sale'] !== '' ? $_POST['sale'] : null;
    $product_image = isset($_POST['imageplace']) && $_POST['imageplace'] !== '' ? $_POST['imageplace'] : null;
    $product_Feauture = isset($_POST['Feauture']) ? $_POST['Feauture'] : null;

    $link = startlink();

    // Prepare the fields and parameters for the prepared statement
    $fields = [];
    $params = [];
    $types = '';

    if ($product_name !== null) {
        $fields[] = "productname=?";
        $params[] = $product_name;
        $types .= 's';
    }
    if ($product_price !== null) {
        $fields[] = "price=?";
        $params[] = $product_price;
        $types .= 'd';
    }
    if ($product_author !== null) {
        $fields[] = "author=?";
        $params[] = $product_author;
        $types .= 's';
    }
    if ($product_show_on_web !== null) {
        $fields[] = "show_on_web=?";
        $params[] = $product_show_on_web;
        $types .= 'i';
    }
    if ($product_sale !== null) {
        $fields[] = "sale=?";
        $params[] = $product_sale;
        $types .= 'd';
    }
    if ($product_image !== null) {
        $fields[] = "imageplace=?";
        $params[] = $product_image;
        $types .= 's';
    }
    if ($product_Feauture !== null) {
        $fields[] = "Featured=?";
        $params[] = $product_Feauture;
        $types .= 'i';
    }

    if (!empty($fields)) {
        $query = "UPDATE products SET " . implode(", ", $fields) . " WHERE id=?";
        $params[] = $product_id;
        $types .= 'i';

        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $product = getone('products', $product_id);
            $product = mysqli_fetch_assoc($product);

            echo "<table>";
            echo "<tr>";
            echo "<td>" . $product['id'] . "</td>";
            echo "<td>" . $product['productname'] . "</td>";
            echo "<td>" . $product['price'] . "$</td>";
            echo "<td>" . $product['author'] . "</td>";
            echo "<td>" . $product['sale'] . "%</td>";
            echo "<td>" . $product['madeby'] . "</td>";
            echo "<td>" . $product['imageplace'] . "</td>";
            echo "<td>" . ($product['show_on_web'] == "1" ? "true" : "false") . "</td>";
            echo "<td>" . ($product['Featured'] == "1" ? "true" : "false") . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>changes here</td>";
            echo "<td><input type='text' id='bookname' name='bookname' placeholder='Enter book name'></td>";
            echo "<td><input type='number' id='price' name='price' step='0.01' placeholder='Enter price'></td>";
            echo "<td><input type='text' id='author' name='author' placeholder='Enter author'></td>";
            echo "<td><input type='number' id='sale' name='sale' step='0.01' placeholder='Enter sale'></td>";
            echo "<td></td>";
            echo "<td><input type='text' id='imageplace' name='imageplace' placeholder='Enter image place'></td>";
            echo "<td>";
            echo "<select id='show_on_web' name='show_on_web'>";
            if ($product['show_on_web'] == "1") {
                echo "<option value='1'>True</option>";
                echo "<option value='0'>False</option>";
            } else {
                echo "<option value='0'>False</option>";
                echo "<option value='1'>True</option>";
            }
            echo "</select>";
            echo "</td>";
            echo "<td>";
            echo "<select id='Feauture' name='Feauture'>";
            if ($product['Featured'] == "1") {
                echo "<option value='1'>True</option>";
                echo "<option value='0'>False</option>";
            } else {
                echo "<option value='0'>False</option>";
                echo "<option value='1'>True</option>";
            }
            echo "</select>";
            echo "</td>";
            echo "</tr>";
            echo "</table>";
        } else {
            echo "Error updating product: " . mysqli_error($link);
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($link);
}
?>