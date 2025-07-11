<?php
session_start();
require_once './functions/functies.php';
checkAuthStatus();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: shop.php");
    exit();
}
if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);
} else {
    echo "User ID not provided!";
}
ErrorHandeler();
$users_result = getone('users', $userId);
$row = mysqli_fetch_assoc($users_result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/userinfo.css">
    <link rel="stylesheet" href="./css/all.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#user-form').submit(function(e) {
                e.preventDefault();
                var id = <?php echo $row['id']; ?>;
                var username = $('#username').val();
                var email = $('#email').val();
                var role = $('#role').val();
                var active = $('#active').val();
                var password = $('#password').val();
                var confirmpas = $('#confirmpas').val();
                if (password != confirmpas) {
                    alert("passwords do not match");
                    return;
                }
                console.log(id, username, email, role, active, password, confirmpas);
                $.ajax({
                    type: "POST",
                    url: "./functions/update_user.php",
                    data: {
                        id: id,
                        username: username,
                        email: email,
                        role: role,
                        active: active,
                        password: password,
                        confirmpas: confirmpas
                    },
                    success: function(response) {
                        $('#user_info').html(response);
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
                <li><a href="admin.php">admin</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="./functions/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="dashboard">
            <div class="users">
                <form id="user-form" action="update_user.php" method="post">
                    <div class="user-list-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>active</th>
                                    <th>change password</th>
                                </tr>
                            </thead>
                            <tbody id=user_info>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['role']; ?></td>
                                    <td><?php echo $row['active'] == "1" ? "true" : "false"; ?></td>
                                    <td><input type="password" id="password" name="password" placeholder="password"></td>
                                </tr>
                                <tr>
                                    <td>changes here</td>
                                    <td><input type="text" id="username" name="username" placeholder="Enter username"></td>
                                    <td><input type="text" id="email" name="email" placeholder="Enter email"></td>
                                    <td>
                                        <select id="role" name="role">
                                            <?php if ($row['role'] == "user") {
                                                echo "<option value='user'>User</option>";
                                                echo "<option value='admin'>Admin</option>";
                                            } else {
                                                echo "<option value='admin'>Admin</option>";
                                                echo "<option value='user'>User</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select id="active" name="active">
                                            <?php if ($row['active'] == "1") {
                                                echo "<option value='1'>True</option>";
                                                echo "<option value='0'>False</option>";
                                            } else {
                                                echo "<option value='0'>False</option>";
                                                echo "<option value='1'>True</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td><input type="password" id="confirmpas" name="confirmpas" placeholder="confirmpas"></td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="button2" type="submit" id=changes value="<?php echo $row['id']; ?>">
                            Save
                        </button>
                </form>
            </div>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Book haven. All rights reserved.</p>
    </footer>
</body>

</html>