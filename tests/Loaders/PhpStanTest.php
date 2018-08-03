<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\TestCase;
use exussum12\CoverageChecker\Loaders\PhpStan;

class PhpStanTest extends TestCase
{
    /** @var  PhpStan */
    protected $stan;

    public function setUp()
    {
        $file = __DIR__ . '/../fixtures/phpstan.txt';
        $this->stan = new PhpStan($file);
    }

    public function testGetOutput()
    {
        $expected = [
            'src/PhpStanLoader.php',
            'src/PhpCsLoader.php',
        ];

        $this->assertSame($expected, $this->stan->parseLines());
    }

    public function testInvalidLine()
    {
        $this->stan->parseLines();
        $this->assertEquals(
            ['Access to an undefined property exussum12\CoverageChecker\PhpStanLoader::$invalidLines.'],
            $this->stan->getErrorsOnLine("src/PhpStanLoader.php", 45)
        );
    }

    public function testValidLine()
    {
        $this->stan->parseLines();
        $this->assertEquals(
            [],
            $this->stan->getErrorsOnLine("src/PhpStanLoader.php", 41)
        );
    }

    public function testNotFoundFile()
    {
        $this->assertTrue($this->stan->handleNotFoundFile());
    }
}
