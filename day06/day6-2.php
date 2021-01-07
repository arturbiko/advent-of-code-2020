<?php

function isNewLine(string $line): bool {
    return \PHP_EOL === $line;
}

$file = @fopen("day6.txt", "r");

$result = 0;

$buffer = "";

$respondents = 0;

while (!feof($file)) {
    $line = fgets($file);

    if (isNewLine($line) || feof($file)) {
        foreach (count_chars($buffer) as $i => $val) {
            if ($val !== $respondents) {
                continue;
            }

            ++$result;
        }

        $buffer = "";
        $respondents = 0;

        if (feof($file)) {
            break;
        }

        continue;
    }

    $line = str_replace(["\n", "\r"], '', $line);;
    $data = str_split($line);

    $buffer .= $line;

    ++$respondents;
}

echo $result;
