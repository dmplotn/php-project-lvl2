<?php

namespace Diff\Generator\Parsing\ParserSelector;

use Symfony\Component\Yaml\Yaml;

function getParser($extName)
{
    $mapping = [
        'json' => fn($data) => json_decode($data, true),
        'yaml' => fn($data) => Yaml::parse($data)
    ];

    return $mapping[$extName] ?? null;
}
