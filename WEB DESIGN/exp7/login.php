<?php
session_start();
include 'db.php';

$username = $password = "";
$username_err = $password_err = $login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty(trim($_POST["username"]))) {
    $username_err = "Please enter username.";
  } else {
    $username = trim($_POST["username"]);
  }

  if (empty(trim($_POST["password"]))) {
    $password_err = "Please enter your password.";
  } else {
    $password = trim($_POST["password"]);
  }

  if (empty($username_err) && empty($password_err)) {
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
      $stmt->bind_result($id, $username_db, $hashed_password);
      $stmt->fetch();
      if (password_verify($password, $hashed_password)) {
        $_SESSION["username"] = $username_db;
        header("location: welcome.php");
        exit();
      } else {
        $login_err = "Invalid password.";
      }
    } else {
      $login_err = "No account found with that username.";
    }
    $stmt->close();
  }
  $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
  <link rel="stylesheet" href="style.css">

  <script>
    function validateLoginForm() {
      let username = document.forms["loginForm"]["username"].value.trim();
      let password = document.forms["loginForm"]["password"].value;
      let errorMsg = "";

      if (username == "") errorMsg += "Username is required.\n";
      if (password == "") errorMsg += "Password is required.\n";

      if (errorMsg != "") {
        alert(errorMsg);
        return false;
      }
      return true;
    }
  </script>
</head>
<body>
    <div class="container">
        
        <h2>Login</h2>

        <?php 
        if (!empty($login_err)) {
            echo '<p style="color:red;">'.$login_err.'</p>';
        }
        ?>

        <form name="loginForm" action="login.php" method="post" onsubmit="return validateLoginForm();">
            <label>Username:</label><br>
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>"><br>
            <span style="color:red;"><?php echo $username_err; ?></span><br>

            <label>Password:</label><br>
            <input type="password" name="password"><br>
            <span style="color:red;"><?php echo $password_err; ?></span><br><br>

            <input type="submit" value="Login">
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
    
    </div>
</body>
</html>
