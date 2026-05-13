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

    .container {
        background: rgba(255, 255, 255, 0.9);
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        text-align: center;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 300px;
        max-height: 90vh;
        overflow-y: auto;
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
function fibonacci($n) {
    // First two terms
    $prev1 = 0;
    $prev2 = 1;

    echo "<div style='text-align: center;'>";
    echo "<h2>Fibonacci Series ($n Terms)</h2>";
    echo "<table border='1' cellspacing='0' cellpadding='10' style='margin: auto; text-align: center;'>";
    echo "<tr><th>Input</th><th>Output</th></tr>";

    for ($i = 0; $i < $n; $i++) {
        if ($i == 0) {
            $input = "0";
            $output = 0;
        } elseif ($i == 1) {
            $input = "1";
            $output = 1;
        } else {
            $output = $prev1 + $prev2;
            $input = "$prev1 + $prev2";
        }

        // Get color
        $color = getColor($output);
        $textColor = ($color == "black") ? "white" : "black";

        // Show row
        echo "<tr style='background-color:$color; color:$textColor;'><td>$input</td><td>$output</td></tr>";

        // Update for next loop
        $prev1 = $prev2;
        $prev2 = $output;
    }

    echo "</table>";
    echo "</div>";
}

// Coloring logic
function getColor($num) {
    if ($num == 0 || $num == 1) return "black";
    if (isPrime($num)) return "yellow";
    return ($num % 2 == 0) ? "magenta" : "cyan";
}

// Prime check
function isPrime($num) {
    if ($num < 2) return false;
    for ($i = 2; $i <= sqrt($num); $i++) {
        if ($num % $i == 0) return false;
    }
    return true;
}
?>

<!-- Form -->
<div style="text-align: center; margin-bottom: 20px;">
    <form method="GET">
        <label style="font-size: 18px;">Enter number of terms: </label>
        <input type="number" name="n" min="1" required style="font-size: 16px;">
        <button type="submit" style="font-size: 16px;">Generate</button>
    </form>
</div>

<?php
$n = isset($_GET['n']) ? intval($_GET['n']) : 10;
fibonacci($n);
?>
</div>