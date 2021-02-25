<?php

namespace Diff\Generator\DiffGenerator;

use function Funct\Collection\union;
use function Funct\Collection\flatten;
use function Diff\Generator\Parsers\getParser;

function getDiff($data1, $data2)
{
    $keys = array_values(union(array_keys($data1), array_keys($data2)));
    sort($keys);

    $mapped = array_map(function ($key) use ($data1, $data2) {
        $value1 = getValueAsStr($data1[$key] ?? null);
        $value2 = getValueAsStr($data2[$key] ?? null);

        if (!array_key_exists($key, $data1)) {
            $newValue = "  + {$key}: {$value2}";
        } elseif (!array_key_exists($key, $data2)) {
            $newValue = "  - {$key}: {$value1}";
        } elseif ($value1 === $value2) {
            $newValue = "    {$key}: {$value1}";
        } else {
            $newValue = [
                "  - {$key}: {$value1}",
                "  + {$key}: {$value2}"
            ];
        }

        return $newValue;
    }, $keys);

    $inner = implode("\n", flatten($mapped));

    return "{\n{$inner}\n}";
}

function getParsedData($filepath1, $filepath2)
{
    $extName1 = pathinfo($filepath1)['extension'];
    $extName2 = pathinfo($filepath2)['extension'];

    if ($extName1 !== $extName2) {
        throw new \Exception("Extension names are different: '{$extName1}' and '{$extName2}'");
    }

    set_error_handler(function ($errno, $errstr) {
        throw new \Exception($errstr);
    });

    $fileContent1 = file_get_contents($filepath1);
    $fileContent2 = file_get_contents($filepath2);

    restore_error_handler();

    $parse = getParser($extName1);

    $data1 = $parse($fileContent1);
    $data2 = $parse($fileContent2);

    return [$data1, $data2];
}

function genDiff($filepath1, $filepath2)
{
    [$data1, $data2] = getParsedData($filepath1, $filepath2);

    return getDiff($data1, $data2);
}

function getValueAsStr($value)
{
    if (!is_bool($value)) {
        return (string) $value;
    }
    return $value ? 'true' : 'false';
}
