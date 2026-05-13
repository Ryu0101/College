<?php
include 'db.php';
$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $conn->real_escape_string($_POST["name"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $message = $conn->real_escape_string($_POST["message"]);

    $sql = "INSERT INTO visitors (name, email, message) VALUES ('$name', '$email', '$message')";
    $msg = $conn->query($sql) ? "✔️ Submitted successfully!" : "❌ Error: " . $conn->error;
}

$result = $conn->query("SELECT * FROM visitors");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Styled Contact Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #ff6262, #7fd3eb);
            margin: 0;
            padding: 40px 20px;
        }

        h2 {
            color:rgb(255, 255, 255);
        }

        form {
            background: rgba(255, 255, 255, 0.2);
            padding: 20px;
            border-radius: 10px;
            width: 100%;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            margin-bottom: 20px;
            max-width: 400px;
            
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.3);
            color: #000;
        }

        input[type="submit"] {
            background:linear-gradient(to left, #ff6262, #7fd3eb);
            color: white;
            cursor: pointer;

            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        input[type="submit"]:hover {
            background:linear-gradient(to right, #ff6262, #7fd3eb);
        }

        .message {
            margin-top: 10px;
            font-weight: bold;
            color: green;
        }

        table {
            margin: 30px auto 0;
            max-width: 800px;
            width: 100%;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            overflow: hidden;
            color: #fff;
        }

        th, td {
            padding: 12px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            text-align: left;
        }

        th {
            background-color: rgba(0, 0, 0, 0.4);
        }

        tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.1);
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .centered-heading {
            color: #fff;
            text-align: center;
            margin-bottom: 10px;
        }

    </style>
</head>
<body>
    <div class="container">

        <h2 class="centered-heading">Contact Form</h2>
        <form method="post">
            <label>Name:</label>
            <input type="text" name="name" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Message:</label>
            <textarea name="message" rows="4" required></textarea>

            <input type="submit" value="Submit">
        </form>

        <div class="message"><?php echo $msg; ?></div>

        <h2 class="centered-heading">Visitor Entries</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>ID</th><th>Name</th><th>Email</th><th>Message</th>
                </tr>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row["id"] ?></td>
                    <td><?= htmlspecialchars($row["name"]) ?></td>
                    <td><?= htmlspecialchars($row["email"]) ?></td>
                    <td><?= htmlspecialchars($row["message"]) ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No entries found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
