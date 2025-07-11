<?php
session_start();
require_once './functies.php';
checkAuthStatus();
ErrorHandelerFun();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: shop.php");
    exit();
}
if (isset($_POST['search'])) {
    $search = '%' . $_POST['search'] . '%';
    $link = startlink();

    $query = "SELECT * FROM orders WHERE user_id IN (SELECT id FROM users WHERE username LIKE ? OR email LIKE ?)";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "ss", $search, $search);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    while ($order = mysqli_fetch_assoc($res)) {
        $user = getone('users', $order['user_id']);
        $user = mysqli_fetch_assoc($user);
        $username = $user['username'];
        $email = $user['email'];
        ?>
        <tr>
            <td><?php echo $username; ?></td>
            <td><?php echo $email; ?></td>
            <td><?php echo $order['made@']; ?></td>
            <td>
                <button class="button2" onclick="window.location.href='order.php?id=<?php echo $order['id']; ?>'">
                    Order Page
                </button>
            </td>
        </tr>
        <?php
    }

    mysqli_stmt_close($stmt);
    endlink($link);
}
?>