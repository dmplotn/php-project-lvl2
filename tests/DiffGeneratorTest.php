<?php

namespace Diff\Generator\Tests;

use function Diff\Generator\DiffGenerator\genDiff;

class DiffGeneratorTest extends \PHPUnit\Framework\TestCase
{
    private function getFixturePath($filename)
    {
        return __DIR__ . "/fixtures/{$filename}";
    }

    /**
     * @dataProvider additionProvider
     */
    public function testGenDiff($beforeFilename, $afterFilename, $expected)
    {
        $beforePath = $this->getFixturePath($beforeFilename);
        $afterPath = $this->getFixturePath($afterFilename);

        $this->assertEquals($expected, genDiff($beforePath, $afterPath));
    }

    public function additionProvider()
    {
        $result = file_get_contents($this->getFixturePath("result.txt"));

        return [
            ['before.json', 'after.json', $result],
            ['before.yaml', 'after.yaml', $result],
        ];
    }
}
