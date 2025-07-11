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
    <link rel="stylesheet" href="./css/shop2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- https://fontawesome.com/ -->
    <link rel="stylesheet" href="./css/all.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src='./shop2.js'></script>
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <h1>Book Haven</h1>
            </div>
            <div class="nav-links">
                <a href="./shop.php">Home</a>
                <a href="./shop.php#Featured">Featured</a>
                <?php
                if (isset($_SESSION['user_role'])) {
                    if ($_SESSION['user_role'] == 'admin') {
                        echo "<a href='admin.php'>Adminbord</a>";
                    }
                    if ($_SESSION['user_role'] == 'admin' || $_SESSION['user_role'] == 'user') {
                        echo "<a><i class='fas fa-shopping-cart'></i></a>";
                        echo "<button id='Btn_orders' class='fake-link'>Orders</button>";
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
    <div class="top">
        <div class="top-sorting">
            <form id="search-form" action="search_products.php" method="post">
                <div class="group">
                    <svg viewBox="0 0 24 24" aria-hidden="true" class="search-icon">
                        <g>
                            <path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path>
                        </g>
                    </svg>
                    <input id="product-search" class="input" type="search" placeholder="Book/Author" name="searchbar" />
                </div>
                <div class="group_genre">
                    <?php
                    $all_genres = getAllGenre();
                    while ($genre = mysqli_fetch_assoc($all_genres)) {
                        echo "<input type='checkbox' name='genreCheckbox' value='" . $genre['id'] . "'> <label>" . $genre['genre'] . "</label>";
                    }
                    ?>
                </div>
            </form>
        </div>
    </div>
    <section class="books">
        <div class="books-grid">
            <?php
            $result = getProducts();
            while ($products = mysqli_fetch_assoc($result)) {
                echo "<div class='book-card'>";
                echo "<img class='imgshop' src='./photos/" . $products['imageplace'] . "' alt='Book'>";
                echo "<h3 class='bookname'>" . $products['productname'] . "</h3>";

                // Genre container
                echo "<div class='genre-container'>";
                echo "<div class='genre-scroll'>";

                $genres = getAllGenreID($products['id']);
                while ($genre = mysqli_fetch_assoc($genres)) {
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

    <div id="orders-popup" class="popup">
        <div class="popup-content">
            <span class="close_orders">&times;</span>
            <h2>Orders</h2>
            <div id="order-list">
                <!-- Orders will be loaded here -->
            </div>
        </div>
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