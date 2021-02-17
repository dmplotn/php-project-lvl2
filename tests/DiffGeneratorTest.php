<?php

namespace Diff\Generator\Tests;

use function Diff\Generator\DiffGenerator\genDiff;

class DiffGeneratorTest extends \PHPUnit\Framework\TestCase
{
    private function getFixturePath($filename)
    {
        return __DIR__ . "/fixtures/{$filename}";
    }

    public function testGenDiff()
    {
        $beforePath = $this->getFixturePath("beforePlain.json");
        $afterPath = $this->getFixturePath("afterPlain.json");
        $expected = file_get_contents($this->getFixturePath("resultPlain.txt"));

        $this->assertEquals($expected, genDiff($beforePath, $afterPath));
    }
}
