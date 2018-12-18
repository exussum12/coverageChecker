<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\TestCase;
use exussum12\CoverageChecker\Loaders\PhpMnd;

class PhpMndTest extends TestCase
{
    private $mnd;

    public function setUp()
    {
        $file = __DIR__ . '/../fixtures/phpmnd.txt';
        $this->mnd = new PhpMnd($file);

        $this->assertInstanceOf(PhpMnd::class, $this->mnd);
    }

    public function testGetOutput()
    {
        $expected = [
            'test.php',
            'test2.php',
        ];

        $this->assertSame($expected, $this->mnd->parseLines());
    }

    /**
     * @dataProvider fileInputs
     */
    public function testLinesReturnCorrect($filename, $lineNo, $expected)
    {
        $this->mnd->parseLines();

        $this->assertSame($expected, $this->mnd->getErrorsOnLine($filename, $lineNo));
    }

    public function testInvalidFile()
    {
        $this->assertTrue($this->mnd->handleNotFoundFile());
    }

    public function fileInputs()
    {
        return [
            'found file, valid line' => ['test.php', 2, []],
            'found file, invalid line' => ['test.php', 3, ['Magic number: 7']],
            'file not found' => ['otherFile.php', 2, []],
        ];
    }
}
