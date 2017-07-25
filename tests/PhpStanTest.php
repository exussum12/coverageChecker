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
                45 => 'Access to an undefined property',
                51 => 'Access to an undefined property',
            ],
            'src/PhpCsLoader.php' => [
                71 => 'Parameter $message of method',
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
        $this->assertNull($this->stan->handleNotFoundFile());
    }
}
