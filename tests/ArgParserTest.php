<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\TestCase;
use exussum12\CoverageChecker\ArgParser;

class ArgParserTest extends TestCase
{
    public function testNumericArgs()
    {
        $args = [
            'file.php',
            '--some-opt',
            'file',
            '-a',
            'file2',
        ];

        $argParser = new ArgParser($args);
        $this->assertSame("file", $argParser->getArg(1));
        $this->assertSame("file2", $argParser->getArg(2));
        $this->assertNull($argParser->getArg(3));
    }

    public function testAlphaArgs()
    {
        $args = [
            'file.php',
            '--some-opt',
            'file',
            '-a',
            'file2',
        ];

        $argParser = new ArgParser($args);
        $this->assertTrue($argParser->getArg('a'));
        $this->assertTrue($argParser->getArg('some-opt'));
        $this->assertFalse($argParser->getArg('non-existant'));
    }
}
