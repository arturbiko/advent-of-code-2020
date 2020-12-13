<?php

function isNewLine(array $data): bool {
    return 1 === count($data)
        && "\n" === $data[0];
}

function passIsValid(array $pass): bool {
    $required = [
        'byr',
        'iyr',
        'eyr',
        'hgt',
        'hcl',
        'ecl',
        'pid',
    ];

    if (count($pass) > count($required)) {
        return true;
    }

    foreach ($required as $field) {
        if (!array_key_exists($field, $pass)) {
            return false;
        }
    }

    return true;
}

$input = @fopen("input4.txt", "r");

$passport = [];
$passports = [];

$valid = 0;

while (!feof($input)) {
    $buffer = fgets($input);

    $data = explode(' ', $buffer);

    if (isNewLine($data) || feof($input)) {
        $passports[] = $passport;

        if(passIsValid($passport)) {
            ++$valid;
        }

        $passport = [];

        if (feof($input)) {
            break;
        }
    }

    foreach ($data as $information) {
        if ("\n" === $information) {
            continue;
        }

        $details = explode(':', $information);

        $key = $details[0];
        $value = $details[1];

        $passport[$key] = $value;
    }
}

echo $valid;
