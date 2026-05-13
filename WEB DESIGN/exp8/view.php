<?php
include 'db.php';

$result = $conn->query("SELECT id, filename, filecontent FROM uploads ORDER BY uploaded_at DESC");

// Helper function for mime detection
function mime_content_type_from_blob($blob) {
    $tmpfile = tempnam(sys_get_temp_dir(), 'blob');
    file_put_contents($tmpfile, $blob);
    $mime = mime_content_type($tmpfile);
    unlink($tmpfile);
    return $mime;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Uploaded Files</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            min-height: 100vh;
            overflow-x: hidden;
            overflow-y: auto;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: transparent;
        }
        #Particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }
        #Particles canvas {
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            z-index: -1;
        }

        .container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
            width: 90%;
            max-width: 900px;
            margin: 60px auto;
            position: relative;
            color: #fff;
        }
        h2 {
            text-align: center;
            color: #fff;
            margin-bottom: 30px;
        }
        h3 {
            margin-bottom: 5px;
            font-size: 20px;
            color: #ffebcd;
        }
        pre {
            background-color: rgba(255, 255, 255, 0.2);
            padding: 20px;
            border-radius: 10px;
            white-space: pre-wrap;
            word-wrap: break-word;
            /* max-height: 600px; */
            overflow-y: auto;
            font-family: 'Courier New', monospace;
            color: #f5f5f5;
        }
        .back-btn {
            display: block;
            margin: 30px auto 0;
            padding: 12px 20px;
            background:linear-gradient(to right, #ff6262, #7fd3eb);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
        }
        .back-btn:hover {
            background:linear-gradient(to left, #ff6262, #7fd3eb);
        }
        hr {
            border: none;
            height: 1px;
            background: rgba(255, 255, 255, 0.3);
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div id="Particles">
        <canvas></canvas>
    </div>

    <div class="container">
        <h2>Uploaded Files</h2>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <h3><?php echo htmlspecialchars($row['filename']); ?></h3>
                <pre>
    <?php
    $mime = mime_content_type_from_blob($row['filecontent']);
    if (str_starts_with($mime, 'text')) {
        echo htmlspecialchars($row['filecontent']);
    } else {
        echo "(Binary file cannot be displayed as text)";
    }
    ?>
                </pre>
                <hr>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No files uploaded yet.</p>
        <?php endif; ?>

        <a class="back-btn" href="upload.php">⬅ Back to Upload</a>
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
