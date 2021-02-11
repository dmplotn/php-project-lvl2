<?php

namespace Diff\Generator\Cli;

use Docopt;

function run()
{
    $doc = <<<DOC
    Generate diff
    
    Usage:
      gendiff (-h|--help)
      gendiff (-v|--version)
      gendiff [--format <fmt>] <firstFile> <secondFile>
    
    Options:
      -h --help     Show this screen.
      --version     Show version.
      --format <fmt>    Report format [default: stylish]
    DOC;

    Docopt::handle($doc);
}
