<?php
session_start();
require_once './functions/functies.php';
checkAuthStatus();
ErrorHandeler();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Haven</title>
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/shop.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- https://fontawesome.com/ -->
    <link rel="stylesheet" href="./css/all.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#product-search').on('keyup', function() {
                var searchproduct = $(this).val();
                console.log('Search term:', searchproduct);
                $.ajax({
                    url: './functions/shop_Products.php',
                    type: 'POST',
                    data: {
                        search: searchproduct
                    },
                    success: function(response) {
                        $('#books').html(response);
                    }
                });
            });

            $('.fa-shopping-cart').parent().click(function(e) {
                e.preventDefault();
                $('#cart-sidebar').addClass('active');
                CartItems();
                CartTotal();
            });

            $('#close-cart').click(function() {
                $('#cart-sidebar').removeClass('active');
            });

            $(document).on('click', '.add-to-cart', function () {
                var productID = $(this).val();
                console.log('Product ID:', productID);
                $.ajax({
                    url: './functions/add_to_cart.php',
                    type: 'POST',
                    data: {
                        productID: productID,
                    },
                    success: function(response) {
                        CartItems();
                        CartTotal();
                    }

                });
            });
            // forsome reason the remove from cart does not work with the code $('.remove-from-cart').click(function() i think it has somthing to do with the icon i use but i am not sure.
            $(document).on('click', '.remove-from-cart', function() { //https://stackoverflow.com/questions/9418991/when-using-jquery-on-why-use-document-vs-the-element-itself
                var productID = $(this).val();
                console.log('Product ID:', productID);

                $.ajax({
                    url: './functions/remove_from_cart.php',
                    type: 'POST',
                    data: {
                        productID: productID
                    },
                    success: function(response) {
                        CartItems();
                        CartTotal();
                    }
                });
            });
            $(document).on('click', '.plus-btn', function() {
                var productID = $(this).val();
                console.log('Product ID:', productID);
                $.ajax({
                    url: './functions/add_quantity.php',
                    type: 'POST',
                    data: {
                        productID: productID
                    },
                    success: function(response) {
                        CartItems();
                        CartTotal();
                    }
                });
            });
            $(document).on('click', '.minus-btn', function() {
                var productID = $(this).val();
                console.log('Product ID:', productID);
                $.ajax({
                    url: './functions/minus_quantity.php',
                    type: 'POST',
                    data: {
                        productID: productID
                    },
                    success: function(response) {
                        CartItems();
                        CartTotal();
                    }
                });
            });

            function CartItems() {
                $.ajax({
                    url: './functions/get_cart_items.php',
                    type: 'Post',
                    success: function(response) {
                        $('#cart-items').html(response);
                    }
                });
            }

            function CartTotal() {
                $.ajax({
                    url: './functions/get_cart_total.php',
                    type: 'Post',
                    success: function(response) {
                        $('#cart-prijs').html(response);
                    }
                });
            }
        });
    </script>
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <h1>Book Haven</h1>
            </div>
            <div class="nav-links">
                <a href="#home">Home</a>
                <a href="#featured">Featured</a>
                <a href="./shop2.php">All books</a>
                <?php
                if (isset($_SESSION['user_role'])) {
                    if ($_SESSION['user_role'] == 'admin') {
                        echo "<a href='admin.php'>Adminbord</a>";
                    }
                    if ($_SESSION['user_role'] == 'admin' || $_SESSION['user_role'] == 'user') {
                        echo "<a><i class='fas fa-shopping-cart'></i></a>";
                        echo "<a href='./functions/logout.php'>Logout</a>";
                    }
                } else {
                    echo "<a href='login.php'>Login</a>";
                }
                ?>
            </div>
        </nav>
    </header>
    <div id="cart-sidebar" class="cart-sidebar">
        <div class="cart-header">
            <h3>Your Cart</h3>
            <button id="close-cart">&times;</button>
        </div>
        <div id="cart-items" class="cart-items">
            <!-- hier komt dan cart items -->
        </div>
        <div class="cart-footer">
            <div class="cart-total">Total: $<span id="cart-prijs"></span></div>
            <a href="./checkout.php" class="checkout-btn">Checkout</a>
        </div>
    </div>
    <section class="top">
        <div class="top-content">
            <h2>Discover Your Next Adventure</h2>
            <p>Explore thousands of books at amazing prices</p>
            <a href="./shop2.php" class="cta-button">Browse Books</a>
        </div>
    </section>
    <section id="featured" class="featured-books">
        <h2>Featured Books</h2>
        <div class="books-grid">
            <?php
            $result = getProductsFeatured();
            while ($products = mysqli_fetch_assoc($result)) {
                echo "<div class='book-card'>";
                echo "<img class='imgshop' src='./photos/" . $products['imageplace'] . "' alt='Book'>";
                echo "<h3 class='bookname'>" . $products['productname'] . "</h3>";

                // Genre container
                echo "<div class='genre-container'>";
                echo "<div class='genre-scroll'>";

                $genres = getAllGenreID($products['id']);
                while($genre = mysqli_fetch_assoc($genres)) {
                    $res = GetGenre($genre['genreID']);
                    $genre = mysqli_fetch_assoc($res);
                    echo "<span class='genre-pill'>" . $genre['genre'] . "</span>";
                }


                echo "</div>";
                echo "</div>"; 

                echo "<p class='author'>Author: " . $products['author'] . "</p>";
                echo "<div class='book-summary'>";
                echo "<p>" . $products['description'] . "</p>";
                echo "</div>";
                echo "<div class='book-card-footer'>";

                if ($products['sale'] == 0) {
                    echo "<p class='price' value='" . $products['sale'] . "' >Price: $<span class='product-price' value='" . $products['price'] . "'>" . $products['price'] . "</span></p>";
                } else {
                    $cost = $products['price'] - ($products['price'] * $products['sale'] / 100);
                    $cost = number_format($cost, 2);
                    echo "<p class='price'  value='" . $products['sale'] . "' >Price: $<span class='product-price' value='" . $products['price'] . "'>" . $cost . "</span> (Sale: " . $products['sale'] . "%)</p>";
                }
                echo "<button class='add-to-cart' value='" . $products['id'] . "'>Add to Cart</button>";
                echo "</div>";
                echo "</div>";
            }
            ?>
        </div>
    </section>
    <div>
        <button class="button2" id="Btn_shop2" onclick="window.location.href='./shop2.php'"> See all books</button>
    </div>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>About Us</h3>
                <p>Your premier destination for quality books</p>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <p>Email: info@bookhaven.com</p>
                <p>Phone: (04) 99606214</p>
            </div>
            <div class="footer-section">
                <h3>Follow Us</h3>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>