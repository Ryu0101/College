<?php
$host = "localhost";
$user = "root";
$pass = ""; // Change if needed
$dbname = "filedb";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
