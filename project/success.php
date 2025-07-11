<?php
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: shop.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUCCESS</title>
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/success.css">
    <link rel="stylesheet" href="./css/all.css">
    <script>
        setTimeout(function() {
            window.location.href = './shop.php';
        }, 3000);
    </script>
</head>

<body>
    <h1>SUCCESS BESTELLING IS DONE</h1>
</body>

</html>