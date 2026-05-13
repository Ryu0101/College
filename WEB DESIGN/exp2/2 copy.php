<style>
    body, html, #Particles {
        margin: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        font-family: Arial, sans-serif;
    }

    #Particles canvas {
        display: block;
        position: fixed;
        top: 0;
        left: 0;
        z-index: -1;
    }
    .container {
    width: 80%;
    max-width: 900px;
    margin: 0 auto;
    padding: 20px;
    text-align: center;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    max-height: 90vh;
    overflow-y: auto;
    font-family: Arial, sans-serif;
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
<script type="module">
    import { particlesCursor } from 'https://unpkg.com/threejs-toys@0.0.8/build/threejs-toys.module.cdn.min.js';

    const pc = particlesCursor({
        el: document.getElementById('Particles'),
        gpgpuSize: 512,
        colors: [0x00fffc, 0x0000ff],
        color: 0xff0000,
        coordScale: 0.5,
        noiseIntensity: 0.005,
        noiseTimeCoef: 0.0001,
        pointSize: 2,
        pointDecay: 0.0025,
        sleepRadiusX: 250,
        sleepRadiusY: 250,
        sleepTimeCoefX: 0.001,
        sleepTimeCoefY: 0.002
    });
</script>
<div id="Particles">
    <canvas></canvas>
</div>

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