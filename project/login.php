<?php
  $link = session_start();
  $errorlogin = '';
  $email = '';
  $passworduser = '';
  require_once './functions/functies.php'; 
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $email = $_POST['email'];
      $passworduser = $_POST['password'];
      $link = startlink();

      $result1 = $link->prepare("SELECT * FROM users WHERE email = ? AND active = '1'");
      $result1->bind_param("s", $email);
      $result1->execute();
      $result = $result1->get_result();
      if (mysqli_num_rows($result) > 0) 
      {  //check if the email exists
        $row = mysqli_fetch_assoc($result); //fetch the row mysqli_fetch_assoc does not return an array it returns a row works with keys are the row names and values
        if (password_verify($passworduser, $row['pass'])) { //password_verify() checks if the password is correct unhashed password first hashed password second that is fetched from the database
          $_SESSION['user_id'] = $row['id'];
          $_SESSION['user_email'] = $row['email'];
          $_SESSION['user_name'] = $row['username'];
          $_SESSION['user_role'] = $row['role'];
          $_SESSION['active'] = $row['active'];     
          header("Location: shop.php");
          exit();
        } else {
          $errorlogin = "Invalid email or password.";
        }
      }
      else
      {
        $errorlogin = "Invalid email or password.";
      }
      endlink($link);
    }

  ErrorHandeler();
?>

<!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="./css/reset.css" />
    <link rel="stylesheet" href="./css/style.css" />
  </head>
  <body>
    <div class="login_form">
      <form action="login.php" method="POST">
        <h3>Login</h3>
        <div class="input_box">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Enter email address" required />
        </div>
        <p class="error">
            <?php
              if ($errorlogin != "") {
                echo $errorlogin;
              }
            ?>
          </p>
        <div class="input_box">
          <div class="password_title">
            <label for="password">Password</label>
            <a href="daspechpasswordweg">Forgot Password?</a>
          </div>

          <input type="password" id="password" name="password" placeholder="Enter your password" required />
        </div>
        <button type="submit">Log In</button>

        <p class="sign_up">Don't have an account? <a href="./register.php">Sign up</a></p>
      </form>
    </div>
  </body>
</html>