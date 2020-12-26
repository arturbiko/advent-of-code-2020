<?php

function sanitize(string $line): string
{
    return str_replace(PHP_EOL, "", $line);
}

$input = @fopen("day1.txt", "r");

$list = [];

while (!feof($input)) {
    $line = sanitize(fgets($input));

    if (feof($input)) {
        break;
    }

    $list[] = (int) $line;
}

$result = 2020;
$jResult = 0;
$kResult = 0;

$i = 0;

$j = 1;

while ($i < count($list) - 3) {
    $current = $list[$i];
    $result -= $list[$i];

    while ($j < count($list) - 2) {
        $curentJ = $list[$j];
        $jResult = $result - $curentJ;

        if ($jResult <= 0) {
            ++$j;
            continue;
        } else {

            $sibling = array_filter(
                $list,
                function (int $i) use ($jResult, $current, $curentJ) {
                    return 0 === $jResult - $i && $i !== $current && $i !== $curentJ;
                }
            );

            if (count($sibling)) {
                $sibling = array_values($sibling);

                echo sprintf("%d + %d + %d",
                    $list[$i],
                    $list[$j],
                    $sibling[0],
                );
                return;
            } else {
                ++$j;
            }
        }
    }

    $j = 0;
    $result = 2020;
    ++$i;
}
