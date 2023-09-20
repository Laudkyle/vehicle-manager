<?php
session_start(); // Start the session
include 'connection.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['formType'] === 'login') {
        // Get login form data
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Prepare and execute the SQL query
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $con->query($sql);

        if ($result->num_rows > 0) {
            // User found, verify password
            $row = $result->fetch_assoc();
            $hashedPassword = $row['password'];
            $email = $row['email'];
            $phone = $row['phone'];
            $admin = $row['role_id'];

            if (password_verify($password, $hashedPassword)) {
                $_SESSION['loggedin'] = true; 
                $_SESSION['phone'] = $phone;
                $_SESSION['name'] = $row['first_name'] .' '.$row['last_name'];
                $_SESSION['department_id'] = $row['department_id'];
                $_SESSION['position'] = $row['position'];

                $_SESSION['admin'] = $admin;
                
              if ($admin == 1){
                header("Location: admin.php");
              }else if($admin == 101){
                header("Location: drivers.php");
              }else{
                header("Location: index.php");
                exit();
              }
               
            } else {
                // Invalid password
                $errorMessage = "Invalid password";
            }
        } else {
            // User not found
            $errorMessage = "User not found, please check your credentials";
        }
    } 
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login & Signup</title>
  <link rel="stylesheet" href="style1.css">
</head>

<body>
  <div class="hero">
    <div class="form-box" id="formBox">
      <div class="button-box">
        <div id="btn"></div>
        <button type="button" class="toggle-btn" id="1" onclick="login()">Log in</button>
        <button type="button" class="toggle-btn" id="2" onclick="register()">Register</button>
      </div>
      <div class="caption"><h2>Vehicle Management</h2></div>
      <form id="login" class="input-group" action="#" method="post">
      <input type="hidden" name="formType" value="login">
        <input type="text" id="login-email" class="input-field" placeholder="example@bestpointgh.com" name="email" required>
        <input type="password" id="login-password" class="input-field" placeholder="Password" name="password" required><br>
        <a href="forgot.php" class="forgot">Forgotten Password</a>
        <?php if (isset($errorMessage)) { ?>
        <p class="error-message"><?php echo $errorMessage; ?></p>
        <?php } ?>
        <button type="submit" class="submit-btn">Log in</button>

      </form>
      <!-- Register form and other elements -->

      <form id="register" action="#" class="input-group" method="POST">
      <input type="hidden" name="formType" value="register">
      <input type="text" class="input-field" placeholder="First name" name="firstname" required>
      <input type="text" class="input-field" placeholder="last name" name="lastname" required>
      <input type="text" class="input-field" placeholder="User name" name="username" required>
      <input type="text" class="input-field" placeholder="email@email.com" name="email" required>
      <input type="password" class="input-field" placeholder="Password" name="registerPassword" required>
        <input type="text" class="input-field" placeholder="Phone Number" name="registerPhoneNumber" required>
        <button type="submit" class="submit-btn">Register</button>
      </form>
    </div>
  </div>

  <script>
    var x = document.getElementById('login');
    var y = document.getElementById('register');
    var z = document.getElementById('btn');
    var formBox = document.getElementById('formBox');
    var btn_register = document.getElementById('2');
    var btn_login = document.getElementById('1');

    function register() {
      x.style.left = "-400px";
      y.style.left = "30px";
      z.style.left = "110px";
      btn_register.style.color = "white";
      btn_login.style.color = "black";
      formBox.style.height = "600px";

    }

    function login() {
      x.style.left = "30px";
      y.style.left = "430px";
      z.style.left = "0px";
      btn_login.style.color = "white";
      btn_register.style.color = "black";
      formBox.style.height = "430px";
    }
  </script>
</body>

</html>