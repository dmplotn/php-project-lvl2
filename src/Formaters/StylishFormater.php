<?php

namespace Differ\Formaters\StylishFormater;

use function Funct\Collection\flatten;

function formatInner(array $ast, int $depth = 0): string
{
    $parts = array_map(function ($node) use ($depth) {
        $name = $node['name'];
        $type = $node['type'];
        $beforeValue = valueAsStr($node['beforeValue'] ?? null, $depth);
        $afterValue = valueAsStr($node['afterValue'] ?? null, $depth);
        $children = $node['children'] ?? null;

        $indent = str_repeat(' ', 4 * $depth);

        switch ($type) {
            case 'added':
                $part = "{$indent}  + {$name}: {$afterValue}";
                break;
            case 'deleted':
                $part = "{$indent}  - {$name}: {$beforeValue}";
                break;
            case 'changed':
                $part = [
                    "{$indent}  - {$name}: {$beforeValue}",
                    "{$indent}  + {$name}: {$afterValue}"
                ];
                break;
            case 'unchanged':
                $part = "{$indent}    {$name}: {$beforeValue}";
                break;
            case 'nested':
                $inner = formatInner($children, $depth + 1);
                $part = "{$indent}    {$name}: {\n{$inner}\n{$indent}    }";
                break;
            default:
                throw new \LogicException("Unknown node type: {$type}");
        }

        return $part;
    }, $ast);

    return implode("\n", flatten($parts));
}

function formatAst(array $ast): string
{
    $inner = formatInner($ast);
    return "{\n{$inner}\n}";
}

function scalarAsStr($value): string
{
    if (is_null($value)) {
        return 'null';
    }

    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    return (string) $value;
}

function assocInnerAsStr(array $arr, int $depth): string
{
    $keys = array_keys($arr);
    $parts = array_map(function ($key) use ($arr, $depth): string {
        $indent = str_repeat(' ', 4 * $depth);
        $value = valueAsStr($arr[$key], $depth);
        return "{$indent}    {$key}: {$value}";
    }, $keys);

    return implode("\n", $parts);
}

function assocAsStr(array $arr, int $depth): string
{
    $indent = str_repeat(' ', 4 * $depth);
    $inner = assocInnerAsStr($arr, $depth);
    return "{\n{$inner}\n{$indent}}";
}

function valueAsStr($value, int $depth): string
{
    return is_array($value) ? assocAsStr($value, $depth + 1) : scalarAsStr($value);
}
