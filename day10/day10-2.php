<?php

$input = file("day10.txt");

$start = microtime(true);

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

function perm($branch, $input, $permutations, &$lookup, $parent): int {
    if ($branch === (int) end($input)) {
        return 1;
    }

    $stack = [];
    foreach ($input as $n) {
        $temp = (int) $n;

        if ($branch >= $temp) {
            continue;
        }

        if (($temp - $branch) <= 3) {
            $stack[] = $temp;
        } else {
            break;
        }
    }

    $split = $parent;
    if (1 < count($stack)) {

        if (!array_key_exists($branch, $lookup)) {
            $lookup[$branch] = [
                'amount' => 0,
                'completed' => false,
            ];
        }

        $split = $branch;
    }

    if (array_key_exists($branch, $lookup) && $lookup[$branch]['completed']) {
        return $lookup[$branch]['amount'];
    }

    while (0 < count($stack)) {
        $s = array_shift($stack);
        $permutations += perm($s, $input, 0, $lookup, $split);
    }

    if (array_key_exists($branch, $lookup)) {
        $lookup[$branch] = [
            'amount' => $permutations,
            'completed' => true,
        ];
    }

    return $permutations;
}

$lookup = [];

echo perm(0, $input, 0, $lookup, 0);

$time_elapsed_secs = microtime(true) - $start;

echo "\nTime elapsed: ".$time_elapsed_secs;
