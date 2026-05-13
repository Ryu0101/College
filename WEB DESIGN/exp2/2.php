<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #fdfdfd;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 80%;
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
        text-align: center;
    }

    table {
        margin: 0 auto; /* centers the table itself */
        border-collapse: collapse;
        margin-bottom: 30px;
    }

    th, td {
        padding: 10px;
    }

    h2 {
        margin-top: 40px;
    }

    /* Your existing hover effects */
    table tr:hover {
        background-color: #f0f0f0;
        cursor: pointer;
    }

    ol li:hover {
        background-color: #d0f0ff;
        cursor: pointer;
    }

    ul li:hover {
        background-color: #ffe0e0;
        cursor: pointer;
    }

    li {
        padding: 5px;
        margin: 3px 0;
    }

    button {
        padding: 10px 20px;
        font-size: 16px;
        margin-top: 30px;
        cursor: pointer;
    }
</style>

<div class="container">
<?php
// 1. Connect to MySQL
$conn = mysqli_connect("localhost", "root", "", "exp2"); // replace with your DB name

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// 2. Run a SELECT query
$sql = "SELECT * FROM students";
$result = mysqli_query($conn, $sql);

// 3. Display results in a table
echo "<h2>Students Table</h2>";
echo "<table border='1' cellpadding='10'>
<tr>
<th>ID</th>
<th>Name</th>
<th>Age</th>
<th>Email</th>
</tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
    <td>" . $row['id'] . "</td>
    <td>" . $row['name'] . "</td>
    <td>" . $row['age'] . "</td>
    <td>" . $row['email'] . "</td>
    </tr>";
}

echo "</table>";

// 🟡 Ordered List
mysqli_data_seek($result, 0); // Reset before reusing result
echo "<h2>Students - Ordered List</h2>";
echo "<ol>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<li>";
    echo "ID: " . $row['id'] . ", Name: " . $row['name'] . ", Age: " . $row['age'] . ", Email: " . $row['email'];
    echo "</li>";
}
echo "</ol>";

// 🔵 Unordered List
mysqli_data_seek($result, 0); // Reset again before 3rd loop
echo "<h2>Students - Unordered List</h2>";
echo "<ul>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<li>";
    echo "ID: " . $row['id'] . ", Name: " . $row['name'] . ", Age: " . $row['age'] . ", Email: " . $row['email'];
    echo "</li>";
}
echo "</ul>";


// 4. Close connection
mysqli_close($conn);
?>

<form method="post" action="view.php" target="_blank">
    <button type="submit">Click to view in XML</button>
</form>

</div>