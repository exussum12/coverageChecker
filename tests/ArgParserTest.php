<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\TestCase;
use exussum12\CoverageChecker\ArgParser;
use exussum12\CoverageChecker\Exceptions\ArgumentNotFound;

class ArgParserTest extends TestCase
{
    protected $parser;

    public function setUp()
    {
        $args = [
            'file.php',
            '--some-opt=some-val',
            'file',
            '-a',
            'file2',
        ];
        $this->parser = new ArgParser($args);
    }
    public function testNumericArgs()
    {
        $this->assertSame("file", $this->parser->getArg(1));
        $this->assertSame("file2", $this->parser->getArg(2));
    }

    public function testInvalidNumericalArg()
    {
        $this->expectException(ArgumentNotFound::class);

        $this->parser->getArg(3);
    }

    public function testAlphaArgs()
    {
        $this->assertSame("1", $this->parser->getArg('a'));
    }

    public function testInvalidAlphaArgs()
    {
        $this->expectException(ArgumentNotFound::class);

        $this->parser->getArg('non-existent');
    }

    public function testArgumentsWithValues()
    {
        $this->assertEquals('some-val', $this->parser->getArg('some-opt'));
    }
}
