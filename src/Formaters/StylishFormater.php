<?php

namespace Differ\Formaters\StylishFormater;

use function Funct\Collection\flatten;

function formatInner($ast, $depth = 0)
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

function formatAst($ast)
{
    $inner = formatInner($ast);
    return "{\n{$inner}\n}";
}

function scalarAsStr($value)
{
    if (is_null($value)) {
        return 'null';
    }

    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    return $value;
}

function assocInnerAsStr($arr, $depth)
{
    $keys = array_keys($arr);
    $parts = array_map(function ($key) use ($arr, $depth) {
        $indent = str_repeat(' ', 4 * $depth);
        $value = valueAsStr($arr[$key], $depth);
        return "{$indent}    {$key}: {$value}";
    }, $keys);

    return implode("\n", $parts);
}

function assocAsStr($arr, $depth)
{
    $indent = str_repeat(' ', 4 * $depth);
    $inner = assocInnerAsStr($arr, $depth);
    return "{\n{$inner}\n{$indent}}";
}

function valueAsStr($value, $depth)
{
    return is_array($value) ? assocAsStr($value, $depth + 1) : scalarAsStr($value);
}
