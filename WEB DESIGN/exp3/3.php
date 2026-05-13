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
