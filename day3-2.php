<?php

$file = @fopen("data/day3.txt", "r");

$posCounter = 0;

$trees = 0;

$stepSize = 1;

$rowCounter = 0;

while (false !== ($buffer = fgets($file))) {
    ++$rowCounter;
    $mapWidth = count(str_split($buffer)) - 2;

    if (1 === $rowCounter) {
        $posCounter += 1;
        $nextLine = fgets($file);
    }

    if (false !== ($nextLine = fgets($file))) {
        if ($posCounter > $mapWidth) {
            $posCounter = ($posCounter % $mapWidth) - 1;
        }

        if ("#" === $nextLine[$posCounter]) {
            ++$trees;
        }

        $posCounter += 1;
    }
}

echo $trees;

fclose($file);

