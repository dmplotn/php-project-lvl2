<?php

namespace Diff\Generator\DiffGenerator;

use function Diff\Generator\Parsers\getParser;
use function Diff\Generator\Ast\buildAst;
use function Diff\Generator\Formaters\getFormater;

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

    if ($parse === null) {
        throw new \Exception("Parser for given file extension({$extName1}) not found.");
    }

    $data1 = $parse($fileContent1);
    $data2 = $parse($fileContent2);

    return [$data1, $data2];
}

function genDiff($filepath1, $filepath2, $format = 'stylish')
{
    [$data1, $data2] = getParsedData($filepath1, $filepath2);
    $formatAst = getFormater($format);
    $ast = buildAst($data1, $data2);
    return $formatAst($ast);
}
