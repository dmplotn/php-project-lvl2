<?php

namespace Differ\Ast;

use function Funct\Collection\union;

function buildAst(array $data1, array $data2): array
{
    $keys = union(array_keys($data1), array_keys($data2));
    sort($keys);
    return array_map(function ($key) use ($data1, $data2) {
        if (!array_key_exists($key, $data1)) {
            $node = ['name' => $key, 'type' => 'added', 'afterValue' => $data2[$key]];
        } elseif (!array_key_exists($key, $data2)) {
            $node = ['name' => $key, 'type' => 'deleted', 'beforeValue' => $data1[$key]];
        } elseif (is_array($data1[$key]) && is_array($data2[$key])) {
            $node = ['name' => $key, 'type' => 'nested', 'children' => buildAst($data1[$key], $data2[$key])];
        } elseif ($data1[$key] !== $data2[$key]) {
            $node = ['name' => $key, 'type' => 'changed', 'beforeValue' => $data1[$key], 'afterValue' => $data2[$key]];
        } else {
            $node = ['name' => $key, 'type' => 'unchanged', 'beforeValue' => $data1[$key]];
        }

        return $node;
    }, $keys);
}
