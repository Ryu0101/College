<?php
session_start();
if (!isset($_SESSION["username"])) {
  header("location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Welcome</title>
  <style>
    html, body, #Particles{
      margin: 0;
      padding: 0;
      width: 100%;
      height: 100%;
      font-family: Arial, sans-serif;
      overflow: hidden;
    }

    body {
      display: flex;
      justify-content: center;
      align-items: center;
      /*background: linear-gradient(to right, #74ebd5, #acb6e5);*/
    }

    #Particles canvas {
        display: block;
        position: fixed;
        top: 0;
        left: 0;
        z-index: -1;
    }

    .welcome-container {
      background-color: rgba(255, 255, 255, 0.95);
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
      text-align: center;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      max-width: 500px;
      z-index: 1;
    }

    .welcome-container h2 {
      margin-bottom: 20px;
      color: #333;
    }

    .welcome-container a {
      color: #007BFF;
      text-decoration: none;
      font-weight: bold;
    }

    .welcome-container a:hover {
      text-decoration: underline;
    }

    .welcome-container img {
      margin-top: 20px;
      width: 100%;
      max-width: 300px;
      border-radius: 10px;
    }
  </style>
</head>
<body>
  <div class="welcome-container">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h2>
    <p><a href="logout.php">Logout</a></p>
    <img src="naruto.png" alt="Welcome Image">
  </div>

    <div id="Particles">
        <canvas></canvas>
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
