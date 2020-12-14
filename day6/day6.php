<?php

function isNewLine(string $line): bool {
    return \PHP_EOL === $line;
}

$file = @fopen("day6.txt", "r");

$result = 0;

$buffer = [];

while (!feof($file)) {
    $line = fgets($file);

    if (isNewLine($line) || feof($file)) {
        $result += count($buffer);
        $buffer = [];

        if (feof($file)) {
            break;
        }

        continue;
    }

    $line = str_replace(["\n", "\r"], '', $line);;
    $data = str_split($line);

    if (0 === count($buffer)) {
        $buffer = $data;
    } else {
        $diff = array_diff($data, $buffer);

        if (0 === count($diff)) {
            continue;
        }

        array_push($buffer, ...$diff);
    }
}

echo $result;
