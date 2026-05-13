<!DOCTYPE html>
<html>
<head>
    <title>DSA Sorting Lab - PHP</title>
</head>
<body>
<h2>DSA Sorting Algorithms for Indexed & Associative Arrays</h2>
<form method="post">
    <label>Enter Array (e.g. indexed: 5,3,9 or associative: a:5,b:3,c:9):</label><br>
    <input type="text" name="user_array" placeholder="e.g. 4,1,3 or x:4,y:1,z:3" required><br><br>

    <label>Select Sorting Algorithm:</label><br>
    <select name="algorithm" required>
        <option value="bubble">Bubble Sort</option>
        <option value="insertion">Insertion Sort</option>
        <option value="selection">Selection Sort</option>
        <option value="merge">Merge Sort</option>
        <option value="quick">Quick Sort</option>
        <option value="heap">Heap Sort</option>
        <option value="counting">Counting Sort</option>
        <option value="radix">Radix Sort</option>
    </select><br><br>

    <input type="submit" name="submit" value="Sort Array">
</form>

<?php
// All Sorting Algorithms

function bubbleSort(&$arr) {
    $n = count($arr);
    for ($i = 0; $i < $n-1; $i++) {
        for ($j = 0; $j < $n-$i-1; $j++) {
            if ($arr[$j] > $arr[$j+1]) {
                [$arr[$j], $arr[$j+1]] = [$arr[$j+1], $arr[$j]];
            }
        }
    }
}

function insertionSort(&$arr) {
    $n = count($arr);
    for ($i = 1; $i < $n; $i++) {
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
    $n = count($arr);
    for ($i = 0; $i < $n - 1; $i++) {
        $min_idx = $i;
        for ($j = $i + 1; $j < $n; $j++) {
            if ($arr[$j] < $arr[$min_idx]) {
                $min_idx = $j;
            }
        }
        [$arr[$i], $arr[$min_idx]] = [$arr[$min_idx], $arr[$i]];
    }
}

function mergeSort($arr) {
    if (count($arr) <= 1) return $arr;
    $mid = count($arr) / 2;
    $left = array_slice($arr, 0, $mid);
    $right = array_slice($arr, $mid);
    return merge(mergeSort($left), mergeSort($right));
}
function merge($left, $right) {
    $result = [];
    while (count($left) && count($right)) {
        $result[] = ($left[0] < $right[0]) ? array_shift($left) : array_shift($right);
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
    for ($i = floor($n/2)-1; $i >= 0; $i--) heapify($arr, $n, $i);
    for ($i = $n-1; $i > 0; $i--) {
        [$arr[0], $arr[$i]] = [$arr[$i], $arr[0]];
        heapify($arr, $i, 0);
    }
}
function heapify(&$arr, $n, $i) {
    $largest = $i;
    $l = 2*$i + 1;
    $r = 2*$i + 2;
    if ($l < $n && $arr[$l] > $arr[$largest]) $largest = $l;
    if ($r < $n && $arr[$r] > $arr[$largest]) $largest = $r;
    if ($largest != $i) {
        [$arr[$i], $arr[$largest]] = [$arr[$largest], $arr[$i]];
        heapify($arr, $n, $largest);
    }
}

function countingSort(&$arr) {
    $max = max($arr);
    $count = array_fill(0, $max+1, 0);
    foreach ($arr as $val) $count[$val]++;
    $i = 0;
    for ($j = 0; $j <= $max; $j++) {
        while ($count[$j]-- > 0) {
            $arr[$i++] = $j;
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

    foreach ($arr as $val) {
        $index = (int)(($val / $exp) % 10);
        $count[$index]++;
    }

    for ($i = 1; $i < 10; $i++) {
        $count[$i] += $count[$i - 1];
    }

    for ($i = count($arr) - 1; $i >= 0; $i--) {
        $index = (int)(($arr[$i] / $exp) % 10);
        $output[$count[$index] - 1] = $arr[$i];
        $count[$index]--;
    }

    for ($i = 0; $i < count($arr); $i++) {
        $arr[$i] = $output[$i];
    }
}

// Sorting handlers
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
    }
}

function sortAssociative(&$arr, $algo) {
    $values = array_values($arr);
    sortIndexed($values, $algo);
    $original = $arr;
    $sortedAssoc = [];

    foreach ($values as $val) {
        $key = array_search($val, $original);
        $sortedAssoc[$key] = $val;
        unset($original[$key]);
    }

    $arr = $sortedAssoc;
}

// Main
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
        echo "<h3>Sorted Associative Array (by value):</h3><pre>";
    } else {
        sortIndexed($arr, $algorithm);
        echo "<h3>Sorted Indexed Array:</h3><pre>";
    }
    print_r($arr);
    echo "</pre>";
}
?>
</body>
</html>
