<?php

namespace Diff\Generator\Formatting\FormaterSelector;

use function Diff\Generator\Formatting\Formaters\StylishFormater\formatAst;

function getFormater($format)
{
    $mapping = [
        'stylish' => fn($ast) => formatAst($ast)
    ];

    return $mapping[$format] ?? $mapping['stylish'];
}
