<?php

namespace Diff\Generator\Cli;

use Docopt;

use function Diff\Generator\DiffGenerator\genDiff;

function run()
{
    $doc = <<<DOC
    Generate diff
    
    Usage:
      gendiff (-h|--help)
      gendiff (-v|--version)
      gendiff [--format <fmt>] <firstFile> <secondFile>
    
    Options:
      -h --help         Show this screen.
      --version         Show version.
      --format <fmt>    Report format [default: stylish]
    DOC;

    ['<firstFile>' => $filepath1, '<secondFile>' => $filepath2] = Docopt::handle($doc)->args;

    try {
        $diff = genDiff($filepath1, $filepath2);
        echo "{$diff}\n";
    } catch (\Exception $e) {
        echo "{$e->getMessage()}\n";
    }
}
