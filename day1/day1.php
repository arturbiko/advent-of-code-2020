<?php

function sanitize(string $line): string
{
    return str_replace(PHP_EOL, "", $line);
}

$input = @fopen("day1.txt", "r");

$data = [];

while (!feof($input)) {
    $line = sanitize(fgets($input));

    if (feof($input)) {
        break;
    }

    $data[] = (int) $line;
}

foreach ($data as $current) {
    $solution = array_filter($data, function (int $d) use ($current) {
        return ($current + $d) === 2020;
    });

    if (count($solution) > 0) {
        echo sprintf(
            "%d * %d = %d",
            current($solution),
            $current,
            current($solution) * $current
        );
        break;
    }
}
