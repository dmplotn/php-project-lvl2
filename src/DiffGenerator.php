<?php

namespace Diff\Generator\DiffGenerator;

use function Funct\Collection\union;
use function Funct\Collection\flatten;

function getDiff($data1, $data2)
{
    $keys = array_values(union(array_keys($data1), array_keys($data2)));
    sort($keys);

    $mapped = array_map(function ($key) use ($data1, $data2) {
        $value1 = getValueAsStr($data1[$key] ?? null);
        $value2 = getValueAsStr($data2[$key] ?? null);

        if (!array_key_exists($key, $data1)) {
            return "  + {$key}: {$value2}";
        } elseif (!array_key_exists($key, $data2)) {
            return "  - {$key}: {$value1}";
        } elseif ($value1 === $value2) {
            return "    {$key}: {$value1}";
        } else {
            return [
                "  - {$key}: {$value1}",
                "  + {$key}: {$value2}"
            ];
        }
    }, $keys);

    $inner = implode("\n", flatten($mapped));

    return "{\n{$inner}\n}";
}

function genDiff($filepath1, $filepath2)
{
    set_error_handler(function ($errno, $errstr) {
        throw new \Exception($errstr);
    });

    $data1 = json_decode(file_get_contents($filepath1), true);
    $data2 = json_decode(file_get_contents($filepath2), true);

    restore_error_handler();

    return getDiff($data1, $data2);
}

function getValueAsStr($value)
{
    if (!is_bool($value)) {
        return (string) $value;
    }
    return $value ? 'true' : 'false';
}
