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

        $lines = $this->phan->parseLines();

        $this->assertCount(2, $lines);

        $this->assertContains(
            'Argument 1 (string) is int but \strlen() takes string',
            current($this->phan->getErrorsOnLine($file1, 35))
        );
        $this->assertEquals(
            [],
            $this->phan->getErrorsOnLine($file1, 30)
        );
    }

    public function testNotFoundFile()
    {
        $this->assertTrue($this->phan->handleNotFoundFile());
    }
}
