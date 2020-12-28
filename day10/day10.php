<?php

$input = file("day10.txt");

usort($input, function ($a, $b) {
    $a = (int) $a;
    $b = (int) $b;

    if ($a === $b) {
        return 0;
    }

    return $a > $b
        ? 1
        : -1;
});

$storage = [];
$joltage = 0;

foreach ($input as $n) {
    $current = (int) $n;

    if (($current - $joltage) <= 3) {
        $diff = $current - $joltage;

        $key = (string) $diff;

        if (array_key_exists($key, $storage)) {
            $storage[$key] = ++$storage[$key];
        } else {
            $storage[$key] = 1;
        }

        $joltage = $current;
    }
}

$diff = ((int) end($input) + 3) - $joltage;
$key = (string) $diff;
$storage[$key] = ++$storage[$key];

echo $storage["1"] * $storage["3"];
