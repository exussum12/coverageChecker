<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\TestCase;
use exussum12\CoverageChecker\PhpMndLoader;

class PhpMndTest extends TestCase
{
    public function setUp()
    {
            $file = __DIR__ . "/fixtures/phpmnd.txt";
        $this->mnd = new PhpMndLoader($file);

        $this->assertInstanceOf(PhpMndLoader::class, $this->mnd);
    }

    public function testGetOutput()
    {
        $expected = [
            'test.php' => [
                3 => 'Magic number: 7',
                4 => 'Magic number: 12',
            ],
            'test2.php' => [
                3 => 'Magic number: 7',
                4 => 'Magic number: 12',
            ],
        ];

        $this->assertSame($expected, $this->mnd->getLines());
    }

    /**
     * @dataProvider fileInputs
     */
    public function testLinesReturnCorrect($filename, $lineNo, $expected)
    {
        $this->mnd->getLines();

        $this->assertSame($expected, $this->mnd->isValidLine($filename, $lineNo));
    }

    public function testInvalidFile()
    {
        $this->assertTrue($this->mnd->handleNotFoundFile());
    }

    public function fileInputs()
    {
        return [
            'found file, valid line' => ['test.php', 2, true],
            'found file, invalid line' => ['test.php', 3, false],
            'file not found' => ['otherFile.php', 2, true],
        ];
    }
}
