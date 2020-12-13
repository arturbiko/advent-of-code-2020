<?php

function divide(string $instruction, int $lower, int $upper): array {
    $temp = floor(($upper - $lower) / 2) + 1;

    switch ($instruction) {
        case "F":
        case "L":
            $upper = $upper - $temp;
            break;
        case "B":
        case "R":
            $lower = $lower + $temp;
            break;
        default:
    }

    return [
        $lower,
        $upper
    ];
}

$ids = [];

$input = @fopen("day5.txt", "r");

while (!feof($input)) {
    $buffer = fgets($input);
    if (feof($input)) {
        break;
    }

    $boardingPass = str_split($buffer);

    $lowerRow = 0;
    $upperRow = 127;

    $lowerColumn = 0;
    $upperColumn = 7;

    $lastRowInstruction = null;
    $lastColInstruction = null;

    foreach ($boardingPass as $instruction) {
        switch ($instruction) {
            case "F":
            case "B":
                $lastRowInstruction = $instruction;

                list ($loweRowBound, $upperRowBound) = divide($instruction, $lowerRow, $upperRow);

                $lowerRow = $loweRowBound;
                $upperRow = $upperRowBound;
                break;
            case "R":
            case "L":
                $lastColInstruction = $instruction;

                list ($lowerColBound, $upperColBound) = divide($instruction, $lowerColumn, $upperColumn);

                $lowerColumn = $lowerColBound;
                $upperColumn = $upperColBound;
                break;
            default:
                $temp = 0;

                if ("F" === $lastRowInstruction) {
                    $temp = $lowerRow * 8;
                } else {
                    $temp = $upperRow * 8;
                }

                if ("L" === $lastColInstruction) {
                    $temp += $lowerColumn;
                } else {
                    $temp += $upperColumn;
                }

                $ids[] = $temp;

                break;
        }
    }
}

sort($ids);

for ($i = 0; $i < count($ids) - 1; ++$i) {
    $current = $ids[$i];
    $next = $ids[$i + 1];

    if ($next - 1 > $current) {
        echo ($next - 1);
        break;
    }
}

