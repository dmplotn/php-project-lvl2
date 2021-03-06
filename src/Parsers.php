<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function getParser(string $extName): callable
{
    $mapping = [
        'json' => fn($data) => json_decode($data, true),
        'yaml' => fn($data) => Yaml::parse($data)
    ];

    if (!isset($mapping[$extName])) {
        throw new \Exception("Parser for given file extension({$extName}) not found.");
    };

    return $mapping[$extName];
}
