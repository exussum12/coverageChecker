<?php
namespace exussum12\CoverageChecker\tests\Loaders;

use PHPUnit\Framework\TestCase;
use exussum12\CoverageChecker\Loaders\PhanText;
use exussum12\CoverageChecker\tests\TestShim;

class PhanTextTest extends TestCase
{
    use TestShim;
    /** @var  PhanText */
    protected $phan;

    /**
     * @before
     */
    protected function setUpTest()
    {
        $this->phan = new PhanText(__DIR__ . '/../fixtures/phan.txt');
    }

    public function testOutput()
    {
        $file1 = 'src/ArgParser.php';

        $lines = $this->phan->parseLines();

        $this->assertCount(2, $lines);

        $this->assertContainsString(
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
