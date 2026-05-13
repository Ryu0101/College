<?php
include 'db.php';
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $filename = $_FILES['file']['name'];
    $tmp = $_FILES['file']['tmp_name'];
    $fileData = file_get_contents($tmp);

    $stmt = $conn->prepare("INSERT INTO uploads (filename, filecontent) VALUES (?, ?)");
    $stmt->bind_param("ss", $filename, $fileData);
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File - EXP8</title>
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

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 320px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: stretch;
        }

        input[type="file"], input[type="submit"] {
            margin: 10px 0;
            padding: 8px;
            font-size: 1em;
        }

        input[type="submit"] {
            cursor: pointer;
            background:linear-gradient(to right, #ff6262, #7fd3eb);
            color: white;
            border: none;
            border-radius: 5px;
        }

        input[type="submit"]:hover {
            background:linear-gradient(to left, #ff6262, #7fd3eb);
        }

        p.message {
            color: green;
            font-weight: bold;
        }

        a.button-link {
            margin-top: 15px;
            display: inline-block;
            text-decoration: none;
            padding: 8px 15px;
            background:linear-gradient(to left, #ff6262, #7fd3eb);
            color: white;
            border-radius: 5px;
        }

        a.button-link:hover {
            background:linear-gradient(to right, #ff6262, #7fd3eb);
        }
    </style>
</head>
<body>
    <div id="Particles">
        <canvas></canvas>
    </div>

    <div class="form-container">
        <h2>Upload a File</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="file" required>
            <input type="submit" value="Upload">
        </form>
        <p class="message"><?php echo $message; ?></p>
        <a href="view.php" class="button-link">View Uploaded Files</a>
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
