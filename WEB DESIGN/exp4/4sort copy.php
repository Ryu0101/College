<!DOCTYPE html>
<html>
<head>
    <title>Sorting PHP</title>
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
        .form-container {
            position: relative;
            z-index: 1;
            padding-bottom: 100px; /* Optional: ensures space below for dropdown */
        }

        select {
            max-width: 100%;
            z-index: 2;
            position: relative;
        }
    </style>       
</head>
<body>
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
<h2>Array Sorting</h2>
<form method="post">
    <label>Enter Array:<br>
    (Indexed: <code>5,3,9</code> or Associative: <code>a:5,b:3,c:9</code>)</label><br>
    <input type="text" name="user_array" placeholder="e.g. 4,1,3 or x:4,y:1,z:3" required><br><br>
    <label>Select Sorting Algorithm:</label><br>
    <select name="algorithm" required>
        <optgroup label="DSA Sorting">
            <option value="bubble">Bubble Sort</option>
            <option value="insertion">Insertion Sort</option>
            <option value="selection">Selection Sort</option>
            <option value="merge">Merge Sort</option>
            <option value="quick">Quick Sort</option>
            <option value="heap">Heap Sort</option>
            <option value="counting">Counting Sort</option>
            <option value="radix">Radix Sort</option>
        </optgroup>
        <optgroup label="PHP Built-in Sorting">
            <option value="sort">sort() - Ascending (indexed)</option>
            <option value="rsort">rsort() - Descending (indexed)</option>
            <option value="asort">asort() - Value Ascending (assoc)</option>
            <option value="arsort">arsort() - Value Descending (assoc)</option>
            <option value="ksort">ksort() - Key Ascending (assoc)</option>
            <option value="krsort">krsort() - Key Descending (assoc)</option>
        </optgroup>
    </select><br><br>

    <input type="submit" name="submit" value="Sort Array">
</form>

<?php
// ---------- DSA Sorting Algorithms ----------

function bubbleSort(&$arr) {
    for ($i = 0; $i < count($arr)-1; $i++) {
        for ($j = 0; $j < count($arr)-$i-1; $j++) {
            if ($arr[$j] > $arr[$j+1]) {
                [$arr[$j], $arr[$j+1]] = [$arr[$j+1], $arr[$j]];
            }
        }
    }
}

function insertionSort(&$arr) {
    for ($i = 1; $i < count($arr); $i++) {
        $key = $arr[$i];
        $j = $i - 1;
        while ($j >= 0 && $arr[$j] > $key) {
            $arr[$j+1] = $arr[$j];
            $j--;
        }
        $arr[$j+1] = $key;
    }
}

function selectionSort(&$arr) {
    for ($i = 0; $i < count($arr)-1; $i++) {
        $min = $i;
        for ($j = $i+1; $j < count($arr); $j++) {
            if ($arr[$j] < $arr[$min]) $min = $j;
        }
        [$arr[$i], $arr[$min]] = [$arr[$min], $arr[$i]];
    }
}

function mergeSort($arr) {
    if (count($arr) <= 1) return $arr;
    $mid = count($arr)/2;
    $left = array_slice($arr, 0, $mid);
    $right = array_slice($arr, $mid);
    return merge(mergeSort($left), mergeSort($right));
}
function merge($left, $right) {
    $result = [];
    while ($left && $right) {
        $result[] = $left[0] < $right[0] ? array_shift($left) : array_shift($right);
    }
    return array_merge($result, $left, $right);
}

function quickSort($arr) {
    if (count($arr) < 2) return $arr;
    $pivot = $arr[0];
    $left = $right = [];
    for ($i = 1; $i < count($arr); $i++) {
        if ($arr[$i] < $pivot) $left[] = $arr[$i];
        else $right[] = $arr[$i];
    }
    return array_merge(quickSort($left), [$pivot], quickSort($right));
}

function heapSort(&$arr) {
    $n = count($arr);
    for ($i = $n/2 - 1; $i >= 0; $i--) heapify($arr, $n, $i);
    for ($i = $n - 1; $i > 0; $i--) {
        [$arr[0], $arr[$i]] = [$arr[$i], $arr[0]];
        heapify($arr, $i, 0);
    }
}
function heapify(&$arr, $n, $i) {
    $largest = $i;
    $l = 2*$i+1;
    $r = 2*$i+2;
    if ($l < $n && $arr[$l] > $arr[$largest]) $largest = $l;
    if ($r < $n && $arr[$r] > $arr[$largest]) $largest = $r;
    if ($largest != $i) {
        [$arr[$i], $arr[$largest]] = [$arr[$largest], $arr[$i]];
        heapify($arr, $n, $largest);
    }
}

function countingSort(&$arr) {
    $max = max($arr);
    $count = array_fill(0, $max + 1, 0);
    foreach ($arr as $val) $count[$val]++;
    $index = 0;
    for ($i = 0; $i <= $max; $i++) {
        while ($count[$i]-- > 0) {
            $arr[$index++] = $i;
        }
    }
}

function radixSort(&$arr) {
    $max = max($arr);
    $exp = 1;
    while ((int)($max/$exp) > 0) {
        countingSortForRadix($arr, $exp);
        $exp *= 10;
    }
}
function countingSortForRadix(&$arr, $exp) {
    $output = array_fill(0, count($arr), 0);
    $count = array_fill(0, 10, 0);

    foreach ($arr as $val) $count[(int)(($val / $exp) % 10)]++;

    for ($i = 1; $i < 10; $i++) $count[$i] += $count[$i-1];

    for ($i = count($arr)-1; $i >= 0; $i--) {
        $idx = (int)(($arr[$i]/$exp)%10);
        $output[$count[$idx] - 1] = $arr[$i];
        $count[$idx]--;
    }

    for ($i = 0; $i < count($arr); $i++) $arr[$i] = $output[$i];
}

// ---------- Sorting Dispatcher ----------

function sortIndexed(&$arr, $algo) {
    switch ($algo) {
        case "bubble": bubbleSort($arr); break;
        case "insertion": insertionSort($arr); break;
        case "selection": selectionSort($arr); break;
        case "merge": $arr = mergeSort($arr); break;
        case "quick": $arr = quickSort($arr); break;
        case "heap": heapSort($arr); break;
        case "counting": countingSort($arr); break;
        case "radix": radixSort($arr); break;
        case "sort": sort($arr); break;
        case "rsort": rsort($arr); break;
    }
}

function sortAssociative(&$arr, $algo) {
    switch ($algo) {
        case "asort": asort($arr); break;
        case "arsort": arsort($arr); break;
        case "ksort": ksort($arr); break;
        case "krsort": krsort($arr); break;
        default:
            // DSA approach
            $temp = array_values($arr);
            sortIndexed($temp, $algo);
            $original = $arr;
            $sorted = [];
            foreach ($temp as $val) {
                $key = array_search($val, $original);
                $sorted[$key] = $val;
                unset($original[$key]);
            }
            $arr = $sorted;
    }
}

// ---------- User Input Handling ----------

if (isset($_POST['submit'])) {
    $input = trim($_POST['user_array']);
    $algorithm = $_POST['algorithm'];
    $arr = [];
    $isAssoc = false;

    if (strpos($input, ':') !== false) {
        $pairs = explode(',', $input);
        foreach ($pairs as $pair) {
            list($k, $v) = explode(':', $pair);
            $arr[trim($k)] = (int)trim($v);
        }
        $isAssoc = true;
    } else {
        $arr = array_map('intval', explode(',', str_replace(' ', '', $input)));
    }

    echo "<h3>Original Array:</h3><pre>";
    print_r($arr);
    echo "</pre>";

    if ($isAssoc) {
        sortAssociative($arr, $algorithm);
        echo "<h3>Sorted Associative Array:</h3><pre>";
    } else {
        sortIndexed($arr, $algorithm);
        echo "<h3>Sorted Indexed Array:</h3><pre>";
    }

    print_r($arr);
    echo "</pre>";
}
?>
</div>
</body>
</html>
