<?php

function sanitize(string $line): string
{
    return str_replace(PHP_EOL, "", $line);
}

$input = @fopen("day9.txt", "r");

$bufferSize = 25;
$buffer = [];

$num = -1;

while (!feof($input)) {
    $number = (int) sanitize(fgets($input));

    if (feof($input)) {
        break;
    }

    if (count($buffer) < $bufferSize) {
        $buffer[] = $number;
        continue;
    } else {
        $results = 0;

        foreach ($buffer as $b) {
            $results += count(array_filter($buffer, function(int $n) use ($b, $number) {
                return ($b + $n) === $number;
            }));

            if (0 < $results) {
                break;
            }
        }

        if (0 === $results) {
            $num = $number;
            break;
        }

        array_shift($buffer);
        array_push($buffer, $number);
    }
}

echo $num;
