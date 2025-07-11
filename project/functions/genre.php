<?php
session_start();
require_once 'functies.php';
ErrorHandelerFun();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "Unauthorized access.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $link = startlink();
    $genrearray = $_POST['selectedGenres'];
    $product_id = $_POST['bookID'];

    $genre_ids = getAllGenreID($product_id);
    while ($genre_id = mysqli_fetch_assoc($genre_ids)) {
        $genre_id = $genre_id['genreID'];
        $sql = "DELETE FROM product_genre WHERE genreID = $genre_id AND productID = $product_id";
        mysqli_query($link, $sql);
    }

    foreach ($genrearray as $genre_id) {
        $sql = "INSERT INTO product_genre (productID, genreID) VALUES ($product_id, $genre_id)";
        mysqli_query($link, $sql);
    }

    $all_genres = getAllGenre();
    $linked_genres = getAllGenreID($product_id);
    $linked_genre_ids = [];
    while ($linked_genre = mysqli_fetch_assoc($linked_genres)) {
        $linked_genre_ids[] = $linked_genre['genreID'];
    }

    while ($genre = mysqli_fetch_assoc($all_genres)) {
        $checked = in_array($genre['id'], $linked_genre_ids) ? "checked" : "";
        echo "<div><input type='checkbox' name='genreCheckbox' value='" . $genre['id'] . "' $checked> " . $genre['genre'] . "</div>";
    }

    endlink($link);
    exit();
}
?>