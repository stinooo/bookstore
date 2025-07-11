<?php
session_start();
require_once './functions/functies.php';
checkAuthStatus();
ErrorHandeler();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: shop.php");
    exit();
}

if (isset($_GET['id'])) {
    $productID = intval($_GET['id']);
} else {
    echo "User ID not provided!";
}



$products_result = getone('products', $productID);
$product = mysqli_fetch_assoc($products_result);

$res = getone('users', $product['madeby']);
$user = mysqli_fetch_assoc($res);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/product.css">
    <link rel="stylesheet" href="./css/all.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#product-form').submit(function(e) {
                e.preventDefault();
                var bookname = $('#bookname').val();
                var price = $('#price').val();
                var author = $('#author').val();
                var description = $('#description').val();
                var imageplace = $('#imageplace').val();
                var show_on_web = $('#show_on_web').val();
                var sale = $('#sale').val();
                var Feauture = $('#Feauture').val();
                var bookID = $('#Btn_save').val();
                console.log(bookname, price, author, description, imageplace, show_on_web, sale, Feauture, bookID);
                $.ajax({
                    url: './functions/update_product.php',
                    type: 'POST',
                    data: {
                        bookID: bookID,
                        bookname: bookname,
                        price: price,
                        author: author,
                        description: description,
                        imageplace: imageplace,
                        show_on_web: show_on_web,
                        sale: sale,
                        Feauture: Feauture
                    },
                    success: function(response) {
                        $('#product_info').html(response);
                        updateimg(bookID);
                    }
                });
            });

            function updateimg(productID) {
                $.ajax({
                    url: './functions/update_img.php',
                    type: 'POST',
                    data: {
                        productID: productID
                    },
                    success: function(response) {
                        $('#img').html(response);
                    }
                });
            }

            $('#Btn_Genre_add').click(function() {
                $('#popupcader').show();
            });

            $('#popup_close').click(function() {
                $('#popupcader').hide();
            });

            $('#popup_Save').click(function() {
                var selectedGenres = [];
                $('input[name="genreCheckbox"]:checked').each(function() {
                    selectedGenres.push($(this).val());
                });
                var bookID = $('#Btn_save').val();
                console.log(selectedGenres, bookID);
                $.ajax({
                    url: './functions/genre.php',
                    type: 'POST',
                    data: {
                        bookID: bookID,
                        selectedGenres: selectedGenres
                    },
                    success: function(response) {
                        $('.genre-form').html(response);
                        $('#popupcader').hide();
                    }
                });
            });

            $('#Btn_Genre_make').click(function() {
                $('#popupcader_make').show();
            });

            $('#popup_close_make').click(function() {
                $('#popupcader_make').hide();
            });

            $('#popup_Save_make').click(function() {
                var genreName = $('#new_genre_name').val();
                var bookID = $('#Btn_Genre_make').val();
                console.log(genreName, bookID);
                $.ajax({
                    url: './functions/Make_genre.php',
                    type: 'POST',
                    data: {
                        bookID: bookID,
                        genreName: genreName
                    },
                    success: function(response) {
                        $('#genre-form').html(response);
                        $('#popupcader_make').hide();
                    }
                });
            });
        });
    </script>
</head>

<body>
    <header>
        <h1>Admin Dashboard</h1>
        <nav>
            <ul>
                <ul class="nav-links">
                    <li><a href="admin.php">admin</a></li>
                    <li><a href="shop.php">Shop</a></li>
                    <li><a href="./functions/logout.php">Logout</a></li>
                </ul>
            </ul>
        </nav>
    </header>
    <main>
        <section class="dashboard">
            <div class="wrapper">
                <h2>Product List</h2>
                <div class="container">
                    <form id="product-form" action="update_product.php" method="post">
                        <table>
                            <thead>
                                <tr>
                                    <th>productID</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Author</th>
                                    <th>sale% </th>
                                    <th>Made By userID</th>
                                    <th>Image Place</th>
                                    <th>Active</th>
                                    <th>Featured</th>
                                </tr>
                            </thead>
                            <tbody id="product_info">
                                <tr>
                                    <td><?php echo $product['id']; ?></td>
                                    <td><?php echo $product['productname']; ?></td>
                                    <td><?php echo $product['price']; ?>$</td>
                                    <td><?php echo $product['author']; ?></td>
                                    <td><?php echo $product['sale']; ?>%</td>
                                    <td><?php echo $product['madeby']; ?></td>
                                    <td><?php echo $product['imageplace']; ?></td>
                                    <td><?php echo $product['show_on_web'] == "1" ? "true" : "false"; ?></td>
                                    <td><?php echo $product['Featured'] == "1" ? "true" : "false"; ?></td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>changes here</p>
                                    </td>
                                    <td><input type="text" id="bookname" name="bookname" placeholder="Enter book name"></td>
                                    <td><input type="number" id="price" name="price" step='0.01' placeholder="Enter price"></td>
                                    <td><input type="text" id="author" name="author" placeholder="Enter author"></td>
                                    <td><input type="number" id="sale" name="sale" placeholder="Enter sale"></td>
                                    <td></td>
                                    <td><input type="text" id="imageplace" name="imageplace" placeholder="Enter image place"></td>
                                    <td>
                                        <select id="show_on_web" name="show_on_web">
                                            <?php if ($product['show_on_web'] == "1") {
                                                echo "<option value='1'>True</option>";
                                                echo "<option value='0'>False</option>";
                                            } else {
                                                echo "<option value='0'>False</option>";
                                                echo "<option value='1'>True</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select id="Feauture" name="Feauture">
                                            <?php if ($product['Featured'] == "1") {
                                                echo "<option value='1'>True</option>";
                                                echo "<option value='0'>False</option>";
                                            } else {
                                                echo "<option value='0'>False</option>";
                                                echo "<option value='1'>True</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="button2" id="Btn_save" value="<?php echo $product['id']; ?>" type="submit">SAVE</button>
                    </form>
                </div>
            </div>
            <div class="wrapper">
                <h2>Book Genres</h2>
                <div class="genre-container">
                    <?php
                    echo "<button class='button2' id='Btn_Genre_add' value='" . $product['id'] . "'>See Genre</button>";
                    echo "<button class='button2' id='Btn_Genre_make' value='" . $product['id'] . "'>Make Genre</button>";
                    ?>
                </div>
            </div>
            <div class="wrapper">
                <h2>Book Description</h2>
                <p>
                    <?php
                    echo $product['description'];
                    ?>
                </p>
            </div>
            <div id='img'>
                <?php echo "<img class='imgproduct' id='imgproduct'  src='./photos/" . $product['imageplace'] . "' alt=" . $product['id'] . ">" ?>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Book Store. All rights reserved.</p>
    </footer>

    <div id="popupcader" class="popup">
        <div class="popup-content">
            <span class="close" id="popup_close">&times;</span>
            <h2>Select Genres</h2>
            <form id="genre-form">
                <?php
                $all_genres = getAllGenre();
                $linked_genres = getAllGenreID($product['id']);
                $linked_genre_ids = [];
                while ($linked_genre = mysqli_fetch_assoc($linked_genres)) {
                    $linked_genre_ids[] = $linked_genre['genreID'];
                }

                while ($genre = mysqli_fetch_assoc($all_genres)) {
                    $checked = in_array($genre['id'], $linked_genre_ids) ? "checked" : "";
                    echo "<div><input type='checkbox' name='genreCheckbox' value='" . $genre['id'] . "' $checked> " . $genre['genre'] . "</div>";
                }
                ?>
                <button type="button" class='button2' id="popup_Save">Save</button>
            </form>
        </div>
    </div>

    <!-- Modal for creating a new genre -->
    <div id="popupcader_make" class="popup">
        <div class="popup-content">
            <span class="close" id="popup_close_make">&times;</span>
            <h2>Create New Genre</h2>
            <form id="make-genre-form">
                <div>
                    <label>Genre Name:</label>
                    <input type="text" id="new_genre_name" name="new_genre_name" placeholder="Enter genre name">
                </div>
                <button type="button" class='button2' id="popup_Save_make">Save</button>
            </form>
        </div>
    </div>
</body>

</html>