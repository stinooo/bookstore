<?php
session_start();
require_once './functions/functies.php';
ErrorHandeler();
//Did this bc wanrings were annoying
$error_message = '';
$username = '';
$email = '';
$inuseU = '';
$inuseM = '';
$notsamepass = '';
// Check if the form was submitted to then process the data and insert it into the database bc warnings
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $passworduser = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];
  $hashed_password = password_hash($passworduser, PASSWORD_ARGON2I);
  $link = startlink();

  // Check if username already exists
  $query1 = "SELECT * FROM users WHERE username='$username'";
  if (mysqli_num_rows(mysqli_query($link, $query1)) > 0) {
    $inuseU = "Username already exists!!";
  }

  // Check if email already exists
  $query2 = "SELECT * FROM users WHERE email='$email'";
  if (mysqli_num_rows(mysqli_query($link, $query2)) > 0) {
    $inuseM = "Email already in use!!";
  }
  if ($passworduser != $confirm_password) {
    $notsamepass = "Passwords do not match!!";
  }

  if (empty($inuseU) && empty($inuseM) && empty($notsamepass)) {
    $result = $link->prepare("INSERT INTO users (username, email, pass) VALUES (?, ?, ?)");
    $result->bind_param("sss", $username, $email, $hashed_password);
    $result->execute();

    $query = "SELECT * FROM users WHERE username='$username' and email='$email'";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_assoc($result);

    $_SESSION['user_id'] = $row['id'];
    $_SESSION['user_email'] = $row['email'];
    $_SESSION['user_name'] = $row['username'];
    $_SESSION['user_role'] = $row['role'];
    $_SESSION['user_active'] = $row['active']; 
    header("Location: shop.php");
  }

  endlink($link);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register</title>
  <link rel="stylesheet" href="./css/reset.css" />
  <link rel="stylesheet" href="./css/style.css" />
  <script>
    function controle() {
      var username = document.getElementById("username");
      var email = document.getElementById("email");
      var password = document.getElementById("password");
      var confirm_password = document.getElementById("confirm_password");
      if (username.value == "" || email.value == "" || password.value == "" || confirm_password.value == "") {
        document.getElementsByClassName("error").style.display = "block";
        return false;
      }
      if (password.value != confirm_password.value) {
        if (password.value == "" || confirm_password.value == "") {
          document.getElementById("password").style.display = "block";
          return false;
        }
        document.getElementsByID("passwordcon").style.display = "block";
        return false;
      }
      return true;
    }
  </script>
</head>

<body>
  <div class="login_form">
    <form action="register.php" method="post" onsubmit="return controle()">
      <h3>Register Account</h3>
      <div class="input_box">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Enter your username" />
        <p class="error" id="username">
          <?php
          if ($inuseU != "") {
            echo $inuseU;
          }
          ?>
        </p>
      </div>
      <div class="input_box">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" />
        <p class="error" id="email">
          <?php
          if ($inuseM != "") {
            echo $inuseM;
          }
          ?>
        </p>
      </div>
      <div class="input_box">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" />
      </div>
      <div class="input_box">
        <label for="confirm_password">Confirm Password</label>
        <p class="error" id="passwordcon">
          <?php
          if ($notsamepass != "") {
            echo $notsamepass;
          }
          ?>
        </p>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Enter your password" />
      </div>
      <button type="submit">Register</button>
      <p class="sign_up">Already have an account? <a href="./login.php">Login</a></p>
    </form>
  </div>
</body>

</html>