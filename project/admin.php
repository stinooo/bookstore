<?php
session_start();
require_once './functions/functies.php';
checkAuthStatus();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: shop.php");
    exit();
}

$products_result = getall('products');
$products = [];
while ($row = mysqli_fetch_assoc($products_result)) {
    $products[] = $row;
}

$order_result = getall('orders');
$orders = [];
while ($row = mysqli_fetch_assoc($order_result)) {
    $orders[] = $row;
}

$users_result = getall('users');
$users = [];
while ($row = mysqli_fetch_assoc($users_result)) {
    $users[] = $row;
}
ErrorHandeler();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/admin.css">
    <link rel="stylesheet" href="./css/all.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#product-search').on('keyup', function() {
                var searchproduct = $(this).val();
                console.log('Search term:', searchproduct);
                $.ajax({
                    url: './functions/search_Products.php',
                    type: 'POST',
                    data: {
                        search: searchproduct
                    },
                    success: function(response) {
                        $('#product-list-body').html(response);
                    }
                });
            });
            $('#user-search').on('keyup', function() {
                var searchuser = $(this).val();
                console.log('Search term:', searchuser);
                $.ajax({
                    url: './functions/search_Users.php',
                    type: 'POST',
                    data: {
                        search: searchuser
                    },
                    success: function(response) {
                        $('#user-list-body').html(response);
                    }
                });
            });
            $('#order-search').on('keyup', function() {
                var search = $(this).val();
                console.log('Search term:', search);
                $.ajax({
                    url: './functions/search_orders.php',
                    type: 'POST',
                    data: {
                        search: search
                    },
                    success: function(response) {
                        $('#order-list-body').html(response);
                    }
                });
            });
            $('#add-product-form').submit(function(e) { //https://stackoverflow.com/questions/1960240/jquery-ajax-submit-form
                e.preventDefault();
                var bookname = $('#bookname').val();
                var price = $('#price').val();
                var author = $('#author').val();
                var description = $('#description').val();
                var imageplace = $('#imageplace').val();
                var show_on_web = $('#show_on_web').is(':checked') ? 1 : 0;
                var sale = $('#sale').val();
                var Featured = $('#Feauture').is(':checked') ? 1 : 0;
                $.ajax({
                    url: './functions/add_product.php',
                    type: 'POST',
                    data: {
                        bookname: bookname,
                        price: price,
                        author: author,
                        description: description,
                        imageplace: imageplace,
                        show_on_web: show_on_web,
                        sale: sale,
                        Featured: Featured

                    },
                    success: function(response) {
                        $('#product-list-body').html(response);
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
            <ul class="nav-links">
                <li><a href="shop.php">Shop</a></li>
                <li><a href="./functions/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="dashboard">
            <div class="products">
                <h2>Product List</h2>
                <div class="search-bar">
                    <form id="search-form" action="search_products.php" method="post">
                        <div class="group">
                            <svg viewBox="0 0 24 24" aria-hidden="true" class="search-icon">
                                <g>
                                    <path
                                        d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path>
                                </g>
                            </svg>
                            <input
                                id="product-search"
                                class="input"
                                type="search"
                                placeholder="ID/Name/Author"
                                name="searchbar" />
                        </div>
                    </form>
                </div>
                <div class="product-list-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Bookname</th>
                                <th>Author</th>
                                <th>Price</th>
                                <th>%Sale</th>
                                <th>Made By User</th>
                                <th>Image Place</th>
                                <th>Active</th>
                                <th>Product Settings</th>
                            </tr>
                        </thead>
                        <tbody id="product-list-body">
                            <?php foreach ($products as $product): ?>
                                <?php $users_result = getone('users', $product['madeby']); ?>
                                <?php $user = mysqli_fetch_assoc($users_result); ?>
                                <tr>
                                    <td><?php echo $product['id']; ?></td>
                                    <td><?php echo $product['productname']; ?></td>
                                    <td><?php echo $product['author']; ?></td>
                                    <td><?php echo $product['price']; ?>$</td>
                                    <td><?php echo $product['sale']; ?>%</td>
                                    <td><?php echo $user['username']; ?></td>
                                    <td><?php echo $product['imageplace']; ?></td>
                                    <td><?php echo $product['show_on_web'] == "1" ? "true" : "false"; ?></td>
                                    <td>
                                        <button class="button2"
                                            onclick="window.location.href='product.php?id=<?php echo $product['id']; ?>'">
                                            Go to product Page
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="add-product">
                <h2>Add Book</h2>
                <div>
                    <form id="add-product-form" action="add_product.php" method="post">
                        <label for="bookname">Book Name:</label>
                        <input type="text" id="bookname" name="bookname" placeholder="Enter book name" required>

                        <label for="author">Author:</label>
                        <input type="text" id="author" name="author" placeholder="Enter author name" required>

                        <label for="price">Price:</label> <!--- https://stackoverflow.com/questions/19011861/is-there-a-float-input-type-in-html5 -->
                        <input type="number" id="price" name="price" min="0" step="0.01" placeholder="Enter price" required>

                        <label for="description">Description:</label>
                        <input type="text" id="description" name="description" placeholder="Enter description">

                        <label for="imageplace">Image Place:</label>
                        <input type="text" id="imageplace" name="imageplace" placeholder="Enter image URL">

                        <label for="sale">Sale:</label>
                        <input type="number" id="sale" name="sale" min="0" max="100" placeholder="Enter sale percentage">

                        <label for="show_on_web">Active:</label>
                        <input type="checkbox" id="show_on_web" name="show_on_web">

                        <label for="Feauture">Feature:</label>
                        <input type="checkbox" id="Feauture" name="Feauture">

                        <button type="submit" id="Btn_add_product" class="button2">Add product</button>
                    </form>
                </div>
            </div>
            <div class="orders">
                <h2 id="ordername">Orders</h2>
                <div>
                    <div class="group">
                        <svg viewBox="0 0 24 24" aria-hidden="true" class="search-icon">
                            <g>
                                <path
                                    d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path>
                            </g>
                        </svg>
                        <input
                            id="order-search"
                            class="input"
                            type="search"
                            placeholder="UserName/Email"
                            name="searchbar" />
                    </div>
                    <div class="order-list-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>By</th>
                                    <th>Email</th>
                                    <th>Order Date</th>
                                    <th>Order Details</th>
                                </tr>
                            </thead>
                            <tbody id="order-list-body">
                                <?php
                                foreach ($orders as $order):
                                ?>
                                    <?php
                                    $user = getone('users', $order['user_id']);
                                    $user = mysqli_fetch_assoc($user);
                                    $username = $user['username'];
                                    $email = $user['email']  ?>
                                    <tr>
                                        <td><?php echo $username; ?></td>
                                        <td><?php echo $email; ?></td>
                                        <td><?php echo $order['made@']; ?></td>
                                        <td>
                                            <button class="button2"
                                                onclick="window.location.href='order.php?id=<?php echo $order['id']; ?>'">
                                                Order
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <div class="users">
                <h2>User List</h2>
                <div class="search-bar">
                    <div class="group">
                        <svg viewBox="0 0 24 24" aria-hidden="true" class="search-icon">
                            <g>
                                <path
                                    d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path>
                            </g>
                        </svg>
                        <input
                            id="user-search"
                            class="input"
                            type="search"
                            placeholder="ID/UserName/Email"
                            name="searchbar" />
                    </div>
                </div>
                <div class="user-list-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>UserName</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>active</th>
                                <th>user settings</th>
                            </tr>
                        </thead>
                        <tbody id="user-list-body">
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo $user['id']; ?></td>
                                    <td><?php echo $user['username']; ?></td>
                                    <td><?php echo $user['email']; ?></td>
                                    <td><?php echo $user['role']; ?></td>
                                    <td><?php echo $user['active'] == "1" ? "true" : "false"; ?></td>
                                    <td>
                                        <button class="button2"
                                            onclick="window.location.href='infouser.php?id=<?php echo $user['id']; ?>'">
                                            Go to User Page
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </section>
    </main>
    <footer>
        <p>&copy; 2024 Book Haven. All rights reserved.</p>
    </footer>
</body>

</html>