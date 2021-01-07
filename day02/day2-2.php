<?php

$input = @fopen("day2.txt", "r");

function sanitize(string $line): string
{
    return str_replace(PHP_EOL, "", $line);
}

function isValid(int $min, int $max, string $chr, string $input): bool
{
    if ($chr === $input[$min - 1] && $chr === $input[$max - 1]) {
        return false;
    }

    return $chr === $input[$min - 1] || $chr === $input[$max - 1];
}

$correct = 0;

while (!feof($input)) {
    $line = sanitize(fgets($input));

    if (feof($input)) {
        break;
    }

    $line = explode(
        ' ',
        $line
    );

    list($min, $max) = explode('-', $line[0]);

    $min  = (int) $min;
    $max  = (int) $max;

    $chr = $line[1][0];

    if (isValid($min, $max, $chr, $line[2])) {
        ++$correct;
    }
}

echo $correct;
