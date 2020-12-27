<?php

function sanitize(string $line): string
{
    return rtrim($line, PHP_EOL);
}

$data = file("day9.txt");

$result = 1639024365;
$buffer = [];

$stack = [];

$j = 0;

for ($i = 0; $i < count($data); ++$i) {
    array_push($buffer, (int) rtrim($data[$i], PHP_EOL));

    if (2 <= count($buffer)) {
        $buffRes = array_sum($buffer);

        if ($result === $buffRes) {
            $f = min($buffer);
            $e = max($buffer);

            echo sprintf("%d + %d = %d", $f, $e, $f + $e);
            break;
        } else if ($result > $buffRes) {
            continue;
        } else {
            $i = $j;
            ++$j;
            $buffer = [];
        }
    }
}
