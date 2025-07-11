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
    
    $query = "SELECT * FROM products WHERE 
              id LIKE ? OR 
              productname LIKE ? OR 
              author LIKE ? or
              madeby LIKE ?";
    $result = $link->prepare($query);
    $searchTerm = "%$search%";
    $result->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    $result->execute();
    $result1 = $result->get_result();


    if ($result1->num_rows == 0) {
        echo "<tr><td colspan='6'>No results found</td></tr>";
    }
    
    while ($product = $result1->fetch_assoc()) {
        
        $users_result = getone('users', $product['madeby'] );
        $user = mysqli_fetch_assoc($users_result);

        echo "<tr>";
        echo "<td>" . $product['id'] . "</td>";
        echo "<td>" . $product['productname'] . "</td>";
        echo "<td>" . $product['price'] . "</td>";
        echo "<td>" . $product['sale'] . "</td>";
        echo "<td>" . $product['author'] . "</td>";
        echo "<td>" . $product['madeby']. $user['username']. "</td>";
        echo "<td>" . $product['imageplace'] . "</td>";
        echo "<td>" . ($product['show_on_web'] == "1" ? "true" : "false") . "</td>";
        echo "<td><button class='button2' onclick=\"window.location.href='infoproduct.php?id=" . $product['id'] . "'\">Go to Product Page</button></td>";
        echo "</tr>";
    }
    
    endlink($link);
}
?>