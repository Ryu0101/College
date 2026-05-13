<?php
include 'db.php';
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $filename = $_FILES['file']['name'];
    $tmp = $_FILES['file']['tmp_name'];
    $fileData = file_get_contents($tmp);

    $stmt = $conn->prepare("INSERT INTO uploads (filename, filecontent) VALUES (?, ?)");
    $stmt->bind_param("sb", $filename, $null);

    // Send data as blob
    $stmt->send_long_data(1, $fileData);

    if ($stmt->execute()) {
        $message = "File uploaded successfully!";
    } else {
        $message = "Upload failed: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload File</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .container {
            background: white;
            padding: 30px;
            margin-top: 50px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 90%;
            max-width: 600px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }

        input[type="file"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            padding: 10px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        input[type="submit"]:hover {
            background: #2980b9;
        }

        .message {
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
            color: green;
        }

        a {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #3498db;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .file-entry {
            background: #fafafa;
            border: 1px solid #ddd;
            border-left: 5px solid #3498db;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        pre {
            overflow-x: auto;
            background: #f0f0f0;
            padding: 10px;
            font-size: 14px;
        }
    </style>

</head>
<body>
<h2>Upload a File</h2>
<form method="post" enctype="multipart/form-data">
    Select file: <input type="file" name="file" required><br><br>
    <input type="submit" value="Upload">
</form>
<p style="color: green;"><?php echo $message; ?></p>
<p><a href="view.php">View Uploaded Files</a></p>
</body>
</html>
