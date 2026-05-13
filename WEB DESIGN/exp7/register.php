<?php
session_start();
include 'db.php';

$username = $email = $password = "";
$username_err = $email_err = $password_err = $register_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Server-side validation
  if (empty(trim($_POST["username"]))) {
    $username_err = "Please enter a username.";
  } else {
    $username = trim($_POST["username"]);
  }

  if (empty(trim($_POST["email"]))) {
    $email_err = "Please enter an email.";
  } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    $email_err = "Invalid email format.";
  } else {
    $email = trim($_POST["email"]);
  }

  if (empty(trim($_POST["password"]))) {
    $password_err = "Please enter a password.";
  } elseif (strlen(trim($_POST["password"])) < 6) {
    $password_err = "Password must have at least 6 characters.";
  } else {
    $password = trim($_POST["password"]);
  }

  // If no errors, insert into DB
  if (empty($username_err) && empty($email_err) && empty($password_err)) {
    // Check if username or email exists
    $sql = "SELECT id FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $register_err = "Username or email already taken.";
    } else {
      // Insert user
      $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
      $stmt = $conn->prepare($sql);
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $stmt->bind_param("sss", $username, $email, $hashed_password);
      if ($stmt->execute()) {
        $_SESSION['username'] = $username;
        header("location: welcome.php");
        exit();
      } else {
        $register_err = "Something went wrong. Please try again.";
      }
    }
    $stmt->close();
  }
  $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Registration Form</title>
  <link rel="stylesheet" href="style.css">

  <script>
    function validateForm() {
      let username = document.forms["regForm"]["username"].value.trim();
      let email = document.forms["regForm"]["email"].value.trim();
      let password = document.forms["regForm"]["password"].value;
      let errorMsg = "";

      if (username == "") errorMsg += "Username is required.\n";
      if (email == "") {
        errorMsg += "Email is required.\n";
      } else {
        let re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!re.test(email)) errorMsg += "Invalid email format.\n";
      }
      if (password.length < 6) errorMsg += "Password must be at least 6 characters.\n";

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

        <h2>Register</h2>

        <?php 
        if (!empty($register_err)) {
            echo '<p style="color:red;">'.$register_err.'</p>';
        }
        ?>

        <form name="regForm" action="register.php" method="post" onsubmit="return validateForm();">
            <label>Username:</label><br>
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>"><br>
            <span style="color:red;"><?php echo $username_err; ?></span><br>

            <label>Email:</label><br>
            <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>"><br>
            <span style="color:red;"><?php echo $email_err; ?></span><br>

            <label>Password:</label><br>
            <input type="password" name="password"><br>
            <span style="color:red;"><?php echo $password_err; ?></span><br><br>

            <input type="submit" value="Register">
        </form>

        <p>Already have an account? <a href="login.php">Login here</a>.</p>

    </div>
</body>
</html>
