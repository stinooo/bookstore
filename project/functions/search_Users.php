<?php
session_start();
require_once 'functies.php';
ErrorHandelerFun();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "Unauthorized";
    exit();
}

if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $link = startlink();
    
    $query = "SELECT * FROM users WHERE 
              id LIKE ? OR 
              username LIKE ? OR 
              email LIKE ?";
              
    $result = $link->prepare($query);
    $searchTerm = "%$search%";
    $result->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    $result->execute();
    $result1 = $result->get_result();
    if ($result1->num_rows == 0) {
        echo "<tr><td colspan='6'>No results found</td></tr>";
    }
    
    while ($user = $result1->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td>" . $user['username'] . "</td>";
        echo "<td>" . $user['email'] . "</td>";
        echo "<td>" . $user['role'] . "</td>";
        echo "<td>" . ($user['active'] == "1" ? "true" : "false") . "</td>";
        echo "<td><button class='button2' onclick=\"window.location.href='infouser.php?id=" . $user['id'] . "'\">Go to User Page</button></td>";
        echo "</tr>";
    }
    
    endlink($link);
}
?>