<?php

namespace Diff\Generator\Formaters;

use function Diff\Generator\Formatting\Formaters\StylishFormater\formatAst as stylishFormatAst;
use function Diff\Generator\Formatting\Formaters\PlainFormater\formatAst as plainFormatAst;

function getFormater($format)
{
    $mapping = [
        'stylish' => fn($ast) => stylishFormatAst($ast),
        'plain' => fn($ast) => plainFormatAst($ast),
        'json' => fn($ast) => json_encode($ast),
    ];

    return $mapping[$format] ?? $mapping['stylish'];
}
