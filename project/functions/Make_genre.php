<?php
session_start();
require_once 'functies.php';
ErrorHandelerFun();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "Unauthorized access.";
    exit();
}

$genreName = $_POST['genreName'];
$product_id = $_POST['bookID'];


if (empty($genreName)) {
    throw new Exception("Genre name is empty");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $link = startlink();
        $genreName = $_POST['genreName'];
        $product_id = $_POST['bookID'];
        // Check if genre already exists
        $query = "SELECT genre FROM genres WHERE genre LIKE ?";
        $res = mysqli_prepare($link, $query);
        $likeGenreName = '%' . $genreName . '%';
        mysqli_stmt_bind_param($res, "s", $likeGenreName);
        mysqli_stmt_execute($res);
        mysqli_stmt_store_result($res);
        if (mysqli_stmt_num_rows($res) > 0) {
            mysqli_stmt_close($res);

            // Fetch all genres and linked genres
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
            exit();
        }
        mysqli_stmt_close($res);

        $query = "INSERT INTO genres (genre) VALUES (?)";
        $res = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($res, "s", $genreName);
        if (!mysqli_stmt_execute($res)) {
            throw new Exception("Error inserting genre: " . mysqli_error($link));
        }
        mysqli_stmt_close($res);
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
    } catch (Exception $oke) {
        ExceptionHandler($oke);
    }
}
