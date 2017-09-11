<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\TestCase;
use exussum12\CoverageChecker\PhanTextLoader;

class PhanTextTest extends TestCase
{
    /** @var  PhanTextLoader */
    protected $phan;
    protected function setUp()
    {
        parent::setUp();
        $this->phan = new PhanTextLoader(__DIR__ . '/fixtures/phan.txt');
    }

    public function testOutput()
    {

        $file1 = 'src/ArgParser.php';
        $file2 = 'src/CoverageCheck.php';
        $expected = [
            $file1 => [
                35 => 'Argument 1 (string) is int but \strlen() takes string',
            ],
            $file2 => [
                172 => 'Argument 3 (message) is int but ' .
                    '\exussum12\CoverageChecker\CoverageCheck::addUnCoveredLine()' .
                    ' takes string defined at ./src/CoverageCheck.php:109'
            ],
        ];

        $lines = $this->phan->getLines();

        $this->assertCount(2, $lines);
        $this->assertContains($expected[$file1][35], $lines[$file1][35]);
        $this->assertContains($expected[$file2][172], $lines[$file2][172]);

        $this->assertFalse($this->phan->isValidLine($file1, 35));
        $this->assertTrue($this->phan->isValidLine($file1, 30));
    }

    public function testNotFoundFile()
    {
        $this->assertTrue($this->phan->handleNotFoundFile());
    }
}
