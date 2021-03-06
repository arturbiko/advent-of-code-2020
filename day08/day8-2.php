<?php

$input = @fopen("day8.txt", "r");

$size = [];
$accumulator = 0;
$tempAccumulator = 0;
$skip = 0;
$lineCounter = 0;
$commandHistory = [];
$changedLines = [];
$hasSwapped = false;

while (!feof($input)) {
    $line = fgets($input);

    if (feof($input)) {
        break;
    }

    array_push($size, mb_strlen($line));

    if (0 < $skip) {
        --$skip;
        continue;
    }

    ++$lineCounter;

    if (in_array($lineCounter, $commandHistory)) {
        fseek($input, 0);
        $size = [];
        $accumulator = 0;
        $commandHistory = [];
        $lineCounter = 0;
        $hasSwapped = false;
        continue;
    }

    $command = explode(
        " ",
        str_replace(PHP_EOL, "", $line)
    );

    $amount = (int) array_pop($command);
    $operation = array_pop($command);

    if (!$hasSwapped && ("nop" === $operation || "jmp" === $operation) && !in_array($lineCounter, $changedLines)) {
        $operation = "nop" === $operation
            ? "jmp"
            : "nop"
        ;
        $changedLines[] = $lineCounter;
        $hasSwapped = true;
    }

    $commandHistory[] = $lineCounter;

    switch ($operation) {
        case "acc":
            $accumulator += $amount;
            break;
        case "jmp":
            $skip = $amount - 1;
            $lineCounter += $skip;

            if ($amount > 0) {
                continue 2;
            } else if (0 === $amount) {
                $skip = 0;
            } else {
                $skip = -1 * $skip;

                $size = array_slice(
                    $size,
                    0,
                    count($size) - $skip
                );
                $offset = array_sum($size);

                fseek($input, $offset);
                $skip = 0;

                continue 2;
            }
            break;
        case "nop":
        default:
            break;
    }
}

echo $accumulator;
