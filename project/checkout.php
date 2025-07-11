<?php

/**
 *https://docs.stripe.com/payments/checkout
 *https://blog.hubspot.com/website/html-dropdown
 */
session_start();
require_once './functions/functies.php';
checkAuthStatus();
ErrorHandeler();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: shop.php");
    exit();
}
$total = 0;
foreach ($_SESSION['cart'] as $products) {
    $price = $products['price'] - ($products['price'] * ($products['sale'] / 100));
    $total += $price * $products['aantal'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/checkout.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#checkout-form').submit(function(e) {
                e.preventDefault();
                var name = $('#name').val();
                var phone = $('#phone').val();
                var address = $('#address').val();
                var city = $('#city').val();
                var country = $('#country').val();
                var postal_code = $('#postal_code').val();
                var payment_method = $('#pay').val();
                var total = <?php echo $total; ?>;
                console.log(name, phone, address, city, country, postal_code, payment_method);
                $.ajax({
                    type: "POST",
                    url: "./functions/add_order.php",
                    data: {
                        name: name,
                        phone: phone,
                        address: address,
                        city: city,
                        country: country,
                        postal_code: postal_code,
                        payment_method: payment_method,
                        total: total
                    },
                    success: function(response) {
                        window.location.href = './success.php';
                    }
                });
            });
        });
    </script>
</head>

<body>
    <div class="checkout-container">
        <div class="checkout-form">
            <h2>Checkout</h2>
            <form id="checkout-form" method="POST">
                <div class="form-section">
                    <h3>Billing Information</h3>
                    <input type="text" id='name' name="name" placeholder="Full Name" required>
                    <input type="tel" id='phone' name="phone" placeholder="Phone Number">
                    <input type="text" id='address' name="address" placeholder="Address" required>
                    <input type="text" id='city' name="city" placeholder="City" required>
                    <input type="text" id='country' name="country" placeholder="Country" required>
                    <input type="text" id='postal_code' name="postal_code" placeholder="Postal Code" required>
                </div>

                <div class="form-section">
                    <h3>Payment Method</h3>
                    <select id='pay' name="payment_method" required>
                        <option value="">Select Payment Method</option>
                        <option value="card">Credit Card</option>
                        <option value="paypal">PayPal</option>
                        <option value="bank">Bank Transfer</option>
                    </select>
                </div>
                <button type="submit" class="checkout-button">Place Order</button>
            </form>
        </div>

        <div class="order-summary">
            <h3>Order Summary</h3>
            <div class="summary-book">
                <?php foreach ($_SESSION['cart'] as $products): ?>
                    <div class="summary-products">
                        <img src="<?php echo $products['pic']; ?>" alt="<?php echo $products['name']; ?>">
                        <div class="products-details">
                            <h4><?php echo $products['name']; ?></h4>
                            <p>Quantity: <?php echo $products['aantal']; ?></p>
                            <p>Price: €<?php echo number_format($price, 2) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="total">
                <h4>Total: €<?php echo number_format($total, 2); ?></h4>
            </div>
        </div>
    </div>
</body>

</html>