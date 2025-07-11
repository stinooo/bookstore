<?php
session_start();
require_once 'functies.php';
ErrorHandelerFun();


if (isset($_POST['search']) || isset($_POST['genres'])) {
    $link = startlink();

    $search = $_POST['search'] ?? '';
    $genres = $_POST['genres'] ?? [];
    $searchTerm = "%$search%";

    // If both search and genres are provided
    if (!empty($search) && !empty($genres)) {
        $query = "
            SELECT DISTINCT p.*
            FROM products p
            LEFT JOIN product_genre pg ON p.id = pg.productID
            LEFT JOIN genres g ON pg.genreID = g.id
            WHERE (p.productname LIKE ? OR p.author LIKE ?) 
            AND g.id IN (" . str_repeat('?,', count($genres) - 1) . "?)
        ";
        //str_repeat('?,', count($params)-1)
        //reapet the ? for the amount of genres -1 want eindig met een , en dat mag niet
        //
        $stmt = mysqli_prepare($link, $query);

        $params = array_merge([$searchTerm, $searchTerm], $genres);
        mysqli_stmt_bind_param($stmt, str_repeat('s', count($params)), ...$params);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        while ($product = mysqli_fetch_assoc($res)) {
            renderProductCard($product, $link);
        }
    }
    // If only search is provided
    elseif (!empty($search)) {
        $query = "SELECT DISTINCT * FROM products WHERE productname LIKE ? OR author LIKE ?";
        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, "ss", $searchTerm, $searchTerm);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        while ($product = mysqli_fetch_assoc($res)) {
            renderProductCard($product, $link);
        }
    }
    elseif (!empty($genres)) {
        foreach ($genres as $GenreID) {
            $query = "SELECT DISTINCT * FROM products WHERE id IN (SELECT productID FROM product_genre WHERE genreID = ?)";
            $stmt = mysqli_prepare($link, $query);
            mysqli_stmt_bind_param($stmt, "i", $GenreID);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            while ($product = mysqli_fetch_assoc($res)) {
                renderProductCard($product, $link);
            }
        }
    }
    else {
        $result = getProducts();
        while ($product = mysqli_fetch_assoc($result)) {
            renderProductCard($product, $link);
        }
    }

    mysqli_close($link);
} else {
    echo "Invalid request.";
}
