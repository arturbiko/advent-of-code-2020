<?php

const FLOOR_TILE = ".";
const EMPTY_SEAT = "L";
const OCCUPIED_SEAT = "#";

$map = file("day11.txt", FILE_IGNORE_NEW_LINES);
$temp = [];

$history = [];

function readMap(int $col, int $row, array $map): ?string
{
    $tempRow = str_split($map[$row]);

    return $tempRow[$col];
}

function readHistory(int $col, int $row, array $history): ?string
{
    return $history["$col/$row"];
}

function checkAdjacentSeats(int $col, int $row, array $data, $read, $maxXBound, $maxYBound): string
{
    $current = call_user_func($read, $col, $row, $data);

    if (FLOOR_TILE === $current) {
        return FLOOR_TILE;
    }

    $occupiedSeats = 0;

    for ($y = -1; $y < 2; ++$y) {
        $tempY = $row + $y;

        if (0 > $tempY || $tempY > $maxYBound) {
            continue;
        }

        for ($x = -1; $x < 2; ++$x) {
            if (0 === $y
                    && 0 === $x) {
                continue;
            }

            $tempX = $col + $x;

            if (0 > $tempX || $tempX > $maxXBound) {
                continue;
            }

            $char = call_user_func($read, $tempX, $tempY, $data);
            if (OCCUPIED_SEAT === $char) {
                ++$occupiedSeats;
            }

            if (4 === $occupiedSeats) {
                return OCCUPIED_SEAT === $current
                    ? EMPTY_SEAT : $current;
            }
        }
    }

    return EMPTY_SEAT === $current && 0 === $occupiedSeats
        ? OCCUPIED_SEAT
        : $current
    ;
}

function countEmptySeats(array $history): int
{
    return count(array_filter($history, function (string $seat) {
        return OCCUPIED_SEAT === $seat;
    }));
}

$history = [];
$maxXBound = strlen($map[0]) - 1;
$maxYBound = count($map) - 1;

while (true) {
    $x = -1;
    $y = -1;
    $buffer = [];
    $data = 0 === count($history)
        ? $map
        : $history;
    $method = 0 === count($history)
        ? 'readMap'
        : 'readHistory';

    foreach ($map as $rows) {
        ++$y;
        $x = -1;

        $cols = str_split($rows);

        foreach ($cols as $col) {
            ++$x;

            $buffer["$x/$y"] = checkAdjacentSeats(
                $x,
                $y,
                $data,
                $method,
                $maxXBound,
                $maxYBound
            );
        }
    }

    if (count($history) > 0) {
        if (0 === count(array_diff_assoc($buffer, $history))) {
             break;
        }
    }

    $history = $buffer;
}

echo countEmptySeats($history);
