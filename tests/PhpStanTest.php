<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\TestCase;
use exussum12\CoverageChecker\PhpStanLoader;

class PhpStanTest extends TestCase
{
    protected $stan;

    public function setUp()
    {
        $file = __DIR__ . "/fixtures/phpstan.txt";
        $this->stan = new PhpStanLoader($file);
    }

    public function testGetOutput()
    {
        $expected = [
            'src/PhpStanLoader.php' => [
                45 => 'Access to an undefined property ' .
                    'exussum12\CoverageChecker\PhpStanLoader::$invalidLines.',
                51 => 'Access to an undefined property ' .
                    'exussum12\CoverageChecker\PhpStanLoader::$invalidLines.',
            ],
            'src/PhpCsLoader.php' => [
                71 => 'Parameter $message of method ' .
                    'exussum12\CoverageChecker\PhpCsLoader::addInvalidLine() has ' .
                    'invalid typehint type exussum12\CoverageChecker\stdClass.',
            ],
        ];

        $this->assertSame($expected, $this->stan->getLines());
    }

    public function testInvalidLine()
    {
        $this->stan->getLines();
        $this->assertFalse($this->stan->isValidLine("src/PhpStanLoader.php", 45));
        $this->assertTrue($this->stan->isValidLine("src/PhpStanLoader.php", 41));
    }

    public function testNotFoundFile()
    {
        $this->assertTrue($this->stan->handleNotFoundFile());
    }
}
