<?php

$file = @fopen("input3.txt", "r");

$posCounter = 0;

$trees = 0;

$stepSize = 7; // substitute for 1, 3, 5, 7

$rowCounter = 0;

while (false !== ($buffer = fgets($file))) {
    ++$rowCounter;
    $mapWidth = count(str_split($buffer)) - 2;

    $posCounter += $stepSize;

    if (1 === $rowCounter) {
        $buffer = fgets($file);
    }

    if ($posCounter > $mapWidth) {
        $posCounter = ($posCounter % $mapWidth) - 1;
    }

    if ("#" === $buffer[$posCounter]) {
        ++$trees;
    }
}

echo $trees;

fclose($file);

