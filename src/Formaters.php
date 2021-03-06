<?php

namespace Differ\Formaters;

use function Differ\Formaters\StylishFormater\formatAst as stylishFormatAst;
use function Differ\Formaters\PlainFormater\formatAst as plainFormatAst;

function getFormater($format)
{
    $mapping = [
        'stylish' => fn($ast) => stylishFormatAst($ast),
        'plain' => fn($ast) => plainFormatAst($ast),
        'json' => fn($ast) => json_encode($ast),
    ];

    return $mapping[$format] ?? $mapping['stylish'];
}
