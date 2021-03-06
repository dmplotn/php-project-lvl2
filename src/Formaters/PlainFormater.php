<?php

namespace Differ\Formaters\PlainFormater;

use function Funct\Collection\flatten;

function formatAst($ast, $nameParts = [])
{
    $parts = array_map(function ($node) use ($nameParts) {
        $name = $node['name'];
        $type = $node['type'];
        $beforeValue = valueAsStr($node['beforeValue'] ?? null);
        $afterValue = valueAsStr($node['afterValue'] ?? null);
        $children = $node['children'] ?? null;
        $fullName = implode('.', [...$nameParts, $name]);

        switch ($type) {
            case 'added':
                $part = "Property '{$fullName}' was added with value: {$afterValue}";
                break;
            case 'deleted':
                $part = "Property '{$fullName}' was removed";
                break;
            case 'nested':
                $part = formatAst($children, [...$nameParts, $name]);
                break;
            case 'changed':
                $part = "Property '{$fullName}' was updated. From {$beforeValue} to {$afterValue}";
                break;
            case 'unchanged':
                $part = [];
                break;
            default:
                throw new \LogicException("Unknown node type {$type}");
        }

        return $part;
    }, $ast);

    return implode("\n", flatten($parts));
}

function valueAsStr($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    if (is_string($value)) {
        return "'{$value}'";
    }

    return is_array($value) ? '[complex value]' : (string) $value;
}
