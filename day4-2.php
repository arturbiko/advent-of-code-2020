<?php

function isNewLine(array $data): bool {
    return 1 === count($data)
        && "\n" === $data[0];
}

function passIsValid(array $pass): bool {
    $required = [
        'byr' => function (string $value): bool {
            if (!is_numeric($value)) {
                return false;
            }

            $year = (int) $value;

            return $year >= 1920
                && $year <= 2002;
        },
        'iyr' => function (string $value): bool {
            if (!is_numeric($value)) {
                return false;
            }

            $year = (int) $value;

            return $year >= 2010
                && $year <= 2020;
        },
        'eyr' => function (string $value): bool {
            if (!is_numeric($value)) {
                return false;
            }

            $year = (int) $value;

            return $year >= 2020
                && $year <= 2030;
        },
        'hgt' => function (string $value): bool {
            if (($pos = strpos($value, 'cm'))) {
                $height = (int) substr($value, 0, $pos);

                return $height >= 150
                    && $height <= 193;
            }

            if (($pos = strpos($value, 'in'))) {
                $height = (int) substr($value, 0, $pos);

                return $height >= 59
                    && $height <= 76;
            }

            return false;
        },
        'hcl' => function (string $value): bool {
            return preg_match('/^#[0-9a-f]{6}/', $value);
        },
        'ecl' => function (string $value): bool {
            return in_array($value, [
                'amb',
                'blu',
                'brn',
                'gry',
                'grn',
                'hzl',
                'oth',
            ]);
        },
        'pid' => function (string $value): bool {
            return preg_match('/^[0-9]{9}$/', $value);
        },
    ];

    foreach ($required as $key => $func) {
        if (!array_key_exists($key, $pass)) {
            return false;
        }

        if (!call_user_func($func, $pass[$key])) {
            return false;
        }
    }

    return true;
}

$file = @fopen("data/day4.txt", "r");

$passport = [];

$valid = 0;

while (!feof($file)) {
    $buffer = fgets($file);

    $data = explode(' ', $buffer);

    if (isNewLine($data) || feof($file)) {
        if(passIsValid($passport)) {
            ++$valid;
        }

        $passport = [];

        if (feof($file)) {
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

        if (($pos = strpos($value, "\n"))) {
            $value = substr($value, 0, $pos);
        }

        $passport[$key] = $value;
    }
}

echo $valid;
