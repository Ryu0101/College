<?php
// Connect to DB
$conn = mysqli_connect("localhost", "root", "", "exp2");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch data
$sql = "SELECT * FROM students";
$result = mysqli_query($conn, $sql);

// Create XML structure
$xml = new SimpleXMLElement('<?xml version="1.0"?><students></students>');

while ($row = mysqli_fetch_assoc($result)) {
    $student = $xml->addChild('student');
    $student->addChild('id', $row['id']);
    $student->addChild('name', $row['name']);
    $student->addChild('age', $row['age']);
    $student->addChild('email', $row['email']);
}

// Set content type to display as XML
Header('Content-type: text/xml');

// Show XML in browser
echo $xml->asXML();

// Close connection
mysqli_close($conn);
?>