<?php
session_start();
require_once './functies.php';
checkAuthStatus();
ErrorHandelerFun();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: shop.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['id'])) {
        echo "No user ID provided.";
        exit();
    }

    $userID = $_POST['id'];
    $username = isset($_POST['username']) && $_POST['username'] !== '' ? $_POST['username'] : null;
    $email = isset($_POST['email']) && $_POST['email'] !== '' ? $_POST['email'] : null;
    $role = isset($_POST['role']) && $_POST['role'] !== '' ? $_POST['role'] : null;
    $active = isset($_POST['active']) ? $_POST['active'] : null;
    $password = isset($_POST['password']) && $_POST['password'] !== '' ? $_POST['password'] : null;
    $confirmpas = isset($_POST['confirmpas']) && $_POST['confirmpas'] !== '' ? $_POST['confirmpas'] : null;

    if ($password !== null && $password !== $confirmpas) {
        echo "Passwords do not match.";
        exit();
    }

    $link = startlink();

    // Prepare the fields and parameters for the prepared statement
    $fields = [];
    $params = [];
    $types = '';

    if ($username !== null) {
        $fields[] = "username=?";
        $params[] = $username;
        $types .= 's';
    }
    if ($email !== null) {
        $fields[] = "email=?";
        $params[] = $email;
        $types .= 's';
    }
    if ($role !== null) {
        $fields[] = "role=?";
        $params[] = $role;
        $types .= 's';
    }
    if ($active !== null) {
        $fields[] = "active=?";
        $params[] = $active;
        $types .= 'i';
    }
    if ($password !== null) {
        $hashed_password = password_hash($password, PASSWORD_ARGON2I);
        $fields[] = "pass=?";
        $params[] = $hashed_password;
        $types .= 's';
    }

    if (!empty($fields)) {
        $query = "UPDATE users SET " . implode(", ", $fields) . " WHERE id=?";
        $params[] = $userID;
        $types .= 'i';

        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $user = getone('users', $userID);
            $user = mysqli_fetch_assoc($user);

            echo "<table>";
            echo "<tr>";
            echo "<td>" . $user['id'] . "</td>";
            echo "<td>" . $user['username'] . "</td>";
            echo "<td>" . $user['email'] . "</td>";
            echo "<td>" . $user['role'] . "</td>";
            echo "<td>" . ($user['active'] == "1" ? "true" : "false") . "</td>";
            echo "<td><input type='password' id='password' name='password' placeholder='password'></td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>changes here</td>";
            echo "<td><input type='text' id='username' name='username' placeholder='Enter username'></td>";
            echo "<td><input type='text' id='email' name='email' placeholder='Enter email'></td>";
            echo "<td>";
            echo "<select id='role' name='role'>";
            if ($user['role'] == "user") {
                echo "<option value='user'>User</option>";
                echo "<option value='admin'>Admin</option>";
            } else {
                echo "<option value='admin'>Admin</option>";
                echo "<option value='user'>User</option>";
            }
            echo "</select>";
            echo "</td>";
            echo "<td>";
            echo "<select id='active' name='active'>";
            if ($user['active'] == "1") {
                echo "<option value='1'>True</option>";
                echo "<option value='0'>False</option>";
            } else {
                echo "<option value='0'>False</option>";
                echo "<option value='1'>True</option>";
            }
            echo "</select>"; 
            echo "</td>";
            echo "<td><input type='password' id='confirmpas' name='confirmpas' placeholder='confirmpas'></td>"; 
            echo "</tr>";
            echo "</table>";
        } else {
            echo "Error updating user: " . mysqli_error($link);
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($link);
}
?>