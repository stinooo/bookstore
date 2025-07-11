<?php
ErrorHandelerFun();

function startlink()
{
    require "secret.php";
    $link = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_NAME) or die("no connection to db");
    return $link;
}

function endlink($link)
{
    mysqli_close($link);
}

function getProductsFeatured()
{
    $link = startlink();
    $query = "SELECT * FROM products WHERE show_on_web = '1' and Featured = '1'";
    //no input so no need for prepared statement
    $result = mysqli_query($link, $query) or die("Error: getProducts");

    endlink($link);
    return $result;
}

function getProducts()
{
    $link = startlink();
    $query = "SELECT * FROM products WHERE show_on_web = '1'";
    //no input so no need for prepared statement
    $result = mysqli_query($link, $query) or die("Error: getProducts");

    endlink($link);
    return $result;
}

function getall($table)
{
    $link = startlink();
    $query = "SELECT * FROM $table";
    $result = mysqli_query($link, $query) or die("Error: all");;
    endlink($link);
    return $result;
}

function getone($table, $id)
{
    $link = startlink();
    $query = "SELECT * FROM $table WHERE id = ? ";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    endlink($link);
    return $result;
}

function checkAuthStatus()
{

    if (isset($_SESSION['user_id'])) {
        $link = startlink();
        $auth = getone('users', $_SESSION['user_id']);
        $user = mysqli_fetch_assoc($auth);
        endlink($link);
        $_SESSION['active'] = $user['active'];
        $_SESSION['user_role'] = $user['role'];
        if ($_SESSION['active'] == 0) {
            session_destroy();
            header("Location: login.php");
            exit();
        }
        return $user;
    }
}

function getAllGenreID($productID)
{
    $link = startlink();
    $query = "SELECT * FROM Product_genre where productID = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "s", $productID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    endlink($link);
    return $result;
}

function getAllGenre()
{
    $link = startlink();
    $query = "SELECT * FROM genres";
    $result = mysqli_query($link, $query);
    endlink($link);
    return $result;
}

function GetGenre($genreID)
{
    $link = startlink();
    $query = "SELECT * FROM genres where id = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "s", $genreID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    endlink($link);
    return $result;
}

function ErrorHandeler()
{
    set_error_handler("handleErrors");
    set_exception_handler("ExceptionHandler");
}

function ErrorHandelerFun()
{
    set_error_handler("handleErrorsFun");
    set_exception_handler("ExceptionHandlerFun");
}

function ExceptionHandler($exception) {
    error_log("Uncaught exception: \n " . $exception->getMessage(), 3, "./errorex.log");
    echo "An error occurred. Please try again later.";
    exit();
}

function ExceptionHandlerFun($exception) {
    error_log("Uncaught exception: \n" . $exception->getMessage(), 3, "../errorex.log");
    echo "An error occurred. Please try again later.";
    exit();
}

function handleErrorsFun($errno, $errMsg, $errFile, $errLine)
{
    $time = date("Y-m-d H:i:s");
    $error = "" . $time . "[ " . $errno . " ]: ";
    $error .= $errMsg;
    $error .= " in file " . $errFile;
    $error .= " on line " . $errLine . "\n";
    error_log($error, 3, '../error.log');
    exit();
}

function handleErrors($errno, $errMsg, $errFile, $errLine)
{
    $time = date("Y-m-d H:i:s");
    $error = "" . $time . "[ " . $errno . " ]: ";
    $error .= $errMsg;
    $error .= " in file " . $errFile;
    $error .= " on line " . $errLine . "\n";
    error_log($error, 3, './error.log');
    exit();
}

function getAllOrders()
{
    $link = startlink();
    $query = "SELECT * FROM orders";
    $result = mysqli_query($link, $query);
    endlink($link);
    return $result;
}

function getOrders($user_id)
{
    $link = startlink();
    $query = "SELECT * FROM orders WHERE user_id = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "s", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    endlink($link);
    return $result;
}



function renderProductCard($product)
{
    echo "<div class='book-card'>";
    echo "<img class='imgshop' src='./photos/" . $product['imageplace'] . "' alt='Book'>";
    echo "<h3 class='bookname'>" . $product['productname'] . "</h3>";

    // Genre container
    echo "<div class='genre-container'>";
    echo "<div class='genre-scroll'>";

    $genres = getAllGenreID($product['id']);
    while ($genre = mysqli_fetch_assoc($genres)) {
        $res = GetGenre($genre['genreID']);
        $genre = mysqli_fetch_assoc($res);
        echo "<span class='genre-pill'>" . $genre['genre'] . "</span>";
    }


    echo "</div>";
    echo "</div>";

    echo "<p class='author'>Author: " . $product['author'] . "</p>";
    echo "<div class='book-summary'>";
    echo "<p>" . $product['description'] . "</p>";
    echo "</div>";
    echo "<div class='book-card-footer'>";

    if ($product['sale'] == 0) {
        echo "<p class='price' value='" . $product['sale'] . "' >Price: $<span class='product-price' value='" . $product['price'] . "'>" . $product['price'] . "</span></p>";
    } else {
        $cost = $product['price'] - ($product['price'] * $product['sale'] / 100);
        $cost = number_format($cost, 2);
        echo "<p class='price'  value='" . $product['sale'] . "' >Price: $<span class='product-price' value='" . $product['price'] . "'>" . $cost . "</span> (Sale: " . $product['sale'] . "%)</p>";
    }
    echo "<button class='add-to-cart' value='" . $product['id'] . "'>Add to Cart</button>";
    echo "</div>";
    echo "</div>";
}


