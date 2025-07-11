<?php
session_start();
require_once './functions/functies.php';
checkAuthStatus();
ErrorHandeler();


if (isset($_GET['id'])) {
    $orderID = intval($_GET['id']);
} else {
    echo "Order ID not provided!";
    exit();
}

// Fetch order details
$link = startlink();

$query = "SELECT * FROM orders WHERE id = ?";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "i", $orderID);
mysqli_stmt_execute($stmt);
$order = mysqli_stmt_get_result($stmt);
$order = mysqli_fetch_assoc($order);

if (!$order) {
    echo "Order not found!";
    exit();
}

// Fetch user details
$user = getone('users', $order['user_id']);
$user = mysqli_fetch_assoc($user);

// Fetch ordered products
$query = "SELECT p.*, op.amount FROM order_items op JOIN products p ON op.product_id = p.id WHERE op.order_id = ?";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "i", $orderID);
mysqli_stmt_execute($stmt);
$products = mysqli_stmt_get_result($stmt);

mysqli_stmt_close($stmt);
endlink($link);

if ($user['id'] !== $_SESSION['user_id'] && $_SESSION['user_role'] !== 'admin') {
    header("Location: shop.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/shop.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- https://fontawesome.com/ -->
    <link rel="stylesheet" href="./css/all.css">
    <link rel="stylesheet" href="./css/order.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                        echo "<a href='./shop2.php'>All books</a>";
                        echo "<a href='./functions/logout.php'>Logout</a>";
                    }
                }
                ?>
            </div>
        </nav>
    </header>
    <main>
        <section class="order-details">
            <h2>Order Details</h2>
            <div class="order-info">
                <p>Order ID: <?php echo $order['id']; ?></p>
                <p>Order Date: <?php echo $order['made@']; ?></p>
                <p>Customer Name: <?php echo $user['username']; ?></p>
                <p>Customer Email: <?php echo $user['email']; ?></p>
                <p>Shipping Address: <?php echo $order['address'] . ' ' . $order['city'] . ' ' . $order['postal_code'] . ' ' . $order['country']; ?></p>
                <p>payed: <?php echo $order['payment_method']; ?></p>
                <p>Total Amount: $<?php echo number_format($order['total'], 2); ?></p>
            </div>
            <h3>Ordered Products</h3>
            <div class="books-grid">
                <?php while ($product = mysqli_fetch_assoc($products)) { ?>
                    <?php
                    $total = 0;
                    $price = $product['price'] - ($product['price'] * ($product['sale'] / 100));
                    $total += $price * $product['amount'];
                    ?>
                    <div class="book-card">
                        <img class="imgshop" src="./photos/<?php echo $product['imageplace']; ?>" alt="Book">
                        <h3 class="bookname"><?php echo $product['productname']; ?></h3>
                        <p class="author">Author: <?php echo $product['author']; ?></p>
                        <p class="price">Price: $<?php echo $price; ?></p>
                        <p class="amount">Quantity: <?php echo $product['amount']; ?></p>
                        <?php if ($product['amount'] != 1) { 
                          echo "<p class='total'>Total: $" . number_format($total, 2) . "</p>";
                        }?>
                        
                    </div>
                <?php } ?>
            </div>
        </section>
    </main>
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