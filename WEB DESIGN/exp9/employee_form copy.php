<?php 
include 'db.php';

// Insert logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $department = $conn->real_escape_string($_POST['department']);
    $designation = $conn->real_escape_string($_POST['designation']);
    $salary = $conn->real_escape_string($_POST['salary']);

    $sql = "INSERT INTO employees (name, email, department, designation, salary)
            VALUES ('$name', '$email', '$department', '$designation', '$salary')";

    if ($conn->query($sql)) {
        echo "<p class='success'>Employee added successfully!</p>";
    } else {
        echo "<p class='error'>Error: " . $conn->error . "</p>";
    }
}

// Get employee list
$empList = $conn->query("SELECT id, name FROM employees");

// Get selected employee details
$empDetails = null;
if (isset($_GET['emp_id'])) {
    $empId = intval($_GET['emp_id']);
    $result = $conn->query("SELECT * FROM employees WHERE id = $empId");
    if ($result->num_rows === 1) {
        $empDetails = $result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Form</title>
    <style>
        body, html, #Particles {
            margin: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;

        }
        #Particles canvas {
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            z-index: -1;
        }
        /* body {
            font-family: Arial, sans-serif;
            background: #f0f4f8;
            margin: 0;
            padding: 40px 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        } */

        .container {
            font-family: Arial, sans-serif;
            max-width: 600px;
            width: 90%;
            background:rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);


            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-height: 90vh;
            overflow-y: auto;
            
        }

        h2, h3 {
            text-align: center;
            color: #333;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-top: 12px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        input[type=submit] {
            background:linear-gradient(to right, #ff6262, #7fd3eb);
            color: white;
            border: none;
            margin-top: 20px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        input[type=submit]:hover {
            background:linear-gradient(to left, #ff6262, #7fd3eb);
        }

        .success {
            color: green;
            text-align: center;
            font-weight: bold;
            margin-top: 10px;
        }

        .error {
            color: red;
            text-align: center;
            font-weight: bold;
            margin-top: 10px;
        }

        /* .details-box {
            margin-top: 30px;
            background: #f9f9f9;
            padding: 20px;
            border-left: 5px solid rgb(127, 219, 228);
            border-radius: 8px;
        } */

        .details-box {
            position: relative;
            margin-top: 30px;
            background: #f9f9f9;
            padding: 20px 20px 20px 30px; /* left padding for pseudo-element */
            border-radius: 8px;
        }

        .details-box::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            border-radius: 8px 0 0 8px; /* Match outer radius if needed */
            background: linear-gradient(to top, #ff6262, #7fd3eb); /* Your gradient */
        }


        .details-box p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div id="Particles">
        <canvas></canvas>
    </div>

    <div class="container">

        <h2>Add Employee</h2>
        <form method="post">
            <label>Name:</label>
            <input type="text" name="name" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Department:</label>
            <input type="text" name="department">

            <label>Designation:</label>
            <input type="text" name="designation">

            <label>Salary:</label>
            <input type="number" step="0.01" name="salary">

            <input type="submit" name="add" value="Add Employee">
        </form>

        <h2>Select Employee to View Details</h2>
        <form method="get">
            <select name="emp_id" onchange="this.form.submit()">
                <option value="">--Select Employee--</option>
                <?php while ($row = $empList->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>" <?= (isset($_GET['emp_id']) && $_GET['emp_id'] == $row['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>

        <?php if ($empDetails): ?>
            <div class="details-box">
                <h3>Employee Details</h3>
                <p><strong>Name:</strong> <?= htmlspecialchars($empDetails['name']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($empDetails['email']) ?></p>
                <p><strong>Department:</strong> <?= htmlspecialchars($empDetails['department']) ?></p>
                <p><strong>Designation:</strong> <?= htmlspecialchars($empDetails['designation']) ?></p>
                <p><strong>Salary:</strong> ₹<?= number_format($empDetails['salary'], 2) ?></p>
            </div>
        <?php endif; ?>

    </div>
    
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
</body>
</html>
