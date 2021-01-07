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

function applyHistoryToMap($history, $map) {
    foreach ($history as $key => $val) {
        list($col, $row) = explode("/", $key);

        $map[(int)$row][(int)$col] = $val;
    }

    return $map;
}

function checkAdjacentSeats(int $col, int $row, array $data, $read, $maxXBound, $maxYBound): string
{
    $current = call_user_func($read, $col, $row, $data);

    if (FLOOR_TILE === $current) {
        return FLOOR_TILE;
    }

    $occupiedSeats = 0;

    $moves = [
        function (int $col, int $row, int $i) { // top left
            $tempX = $col - $i;
            $tempY = $row - $i;
            return [$tempX, $tempY, $tempX >= 0 && $tempY >= 0];
        },
        function (int $col, int $row, int $i) { // top
            $tempY = $row - $i;
            return [$col, $tempY, $tempY >= 0];
        },
        function (int $col, int $row, int $i) use ($maxXBound) { // top right
            $tempX = $col + $i;
            $tempY = $row - $i;
            return [$tempX, $tempY, $tempX <= $maxXBound && $tempY >= 0];
        },
        function (int $col, int $row, int $i) use ($maxXBound) { // right
            $tempX = $col + $i;
            return [$tempX, $row, $tempX <= $maxXBound];
        },
        function (int $col, int $row, int $i) use ($maxXBound, $maxYBound) { // bottom right
            $tempX = $col + $i;
            $tempY = $row + $i;
            return [$tempX, $tempY, $tempX <= $maxXBound && $tempY <= $maxYBound];
        },
        function (int $col, int $row, int $i) use ($maxYBound) { // bottom
            $tempY = $row + $i;
            return [$col, $tempY, $tempY <= $maxYBound];
        },
        function (int $col, int $row, int $i) use ($maxXBound, $maxYBound) { // bottom left
            $tempX = $col - $i;
            $tempY = $row + $i;
            return [$tempX, $tempY, $tempX >= 0 && $tempY <= $maxYBound];
        },
        function (int $col, int $row, int $i) { // left
            $tempX = $col - $i;
            return [$tempX, $row, $tempX >= 0];
        },
    ];

    foreach ($moves as $move) {
        for ($i = 1; (list($tempX, $tempY, $cont) = call_user_func($move, $col, $row, $i)) && $cont; ++$i) {
            $chr = $read($tempX, $tempY, $data);
            if (EMPTY_SEAT === $chr) {
                break;
            }

            if (OCCUPIED_SEAT === $chr) {
                ++$occupiedSeats;

                if (5 === $occupiedSeats) {
                    return OCCUPIED_SEAT === $current
                        ? EMPTY_SEAT : $current;
                }

                break;
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
