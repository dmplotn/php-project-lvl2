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
    public function testGenDiff($beforeFilename, $afterFilename, $formatName, $expected)
    {
        $beforePath = $this->getFixturePath($beforeFilename);
        $afterPath = $this->getFixturePath($afterFilename);

        $this->assertEquals($expected, genDiff($beforePath, $afterPath, $formatName));
    }

    public function additionProvider()
    {
        $stylishResult = file_get_contents($this->getFixturePath("stylishResult.txt"));
        $plainResult = file_get_contents($this->getFixturePath("plainResult.txt"));
        $jsonResult = file_get_contents($this->getFixturePath("jsonResult.txt"));

        return [
            ['before.json', 'after.json', 'stylish', $stylishResult],
            ['before.yaml', 'after.yaml', 'stylish',$stylishResult],
            ['before.json', 'after.json', 'plain', $plainResult],
            ['before.yaml', 'after.yaml', 'plain', $plainResult],
            ['before.json', 'after.json', 'json', $jsonResult],
            ['before.yaml', 'after.yaml', 'json', $jsonResult],
        ];
    }
}
