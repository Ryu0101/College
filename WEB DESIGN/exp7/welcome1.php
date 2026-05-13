<?php
session_start();
if (!isset($_SESSION["username"])) {
  header("location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head><title>Welcome</title></head>
<body>
  <h2>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h2>
  <p><a href="logout.php">Logout</a></p>
</body>
</html>
