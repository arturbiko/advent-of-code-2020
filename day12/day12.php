<?php

function rotateShip(string $facingDirection, string $direction, int $angle): string {
    $t = ["N" => 0, "E" => 90, "S" => 180, "W" => 270];

    $currentAngle = $t[$facingDirection];

    if ("L" === $direction) {
        $n = $currentAngle - $angle;
        if (0 > $n) {
            $n += 360;
        }
    } else {
        $n = ($currentAngle + $angle) % 360;
    }

    return array_flip($t)[$n];
}

function moveShip (string $direction, int $steps, int $tx, int $ty) {
    switch ($direction) {
        case "N":
            return [$tx, $ty + $steps];
        case "S":
            return [$tx, $ty - $steps];
        case "E":
            return [$tx + $steps, $ty];
        case "W":
        default:
            return [$tx - $steps, $ty];
    }
}

$input = @file("day12.txt", FILE_IGNORE_NEW_LINES);

$facingPosition = "E";

$tempEW = 0;
$tempNS = 0;

foreach ($input as $instruction) {
    $temp = str_split($instruction);

    $direction = array_shift($temp);
    $steps = (int) implode("", $temp);

    $positionToMove = $facingPosition;

    switch ($direction) {
        case "L":
        case "R":
            $facingPosition = rotateShip($facingPosition, $direction, $steps);
            continue 2;
        case "F":
            $positionToMove = $facingPosition;
            break;
        default:
            $positionToMove = $direction;
            break;
    }

    list ($tempX, $tempY) = moveShip($positionToMove, $steps, $tempEW, $tempNS);

    if ($tempY !== $tempNS) {
        $tempNS = $tempY;
    }

    if ($tempX !== $tempEW) {
        $tempEW = $tempX;
    }
}

echo abs($tempEW) + abs($tempNS);
