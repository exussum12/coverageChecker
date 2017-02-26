<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\TestCase;
use exussum12\CoverageChecker\PhpCsLoader;
use exussum12\CoverageChecker\PhpCsLoaderStrict;

class LoadPhpcsReportTest extends TestCase
{
    public function testCanMakeClass()
    {
        $phpcs = new PhpCsLoader(__DIR__ . '/fixtures/phpcs.json');
        $invalidLines = $phpcs->getLines();
        $expected = [
            '/full/path/to/file/src/XMLReport.php' => [
                11 => ['Opening brace should be on the line after the declaration; found 1 blank line(s)'],
                12 => ['Line indented incorrectly; expected at least 8 spaces, found 4'],
            ],
        ];

        $this->assertEquals($expected, $invalidLines);
        $this->assertFalse($phpcs->isValidLine('/full/path/to/file/src/XMLReport.php', 11));
        $this->assertTrue($phpcs->isValidLine('/full/path/to/file/src/XMLReport.php', 10));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRejectsInvalidData()
    {
        new PhpCsLoader(__DIR__ . '/fixtures/change.txt');
    }

    public function testCorrectMissingFile()
    {
        $phpcs = new PhpCsLoader(__DIR__ . '/fixtures/phpcs.json');

        $this->assertTrue($phpcs->handleNotFoundFile());
    }

    public function testStrictMode()
    {
        $phpcs = new PhpCsLoaderStrict(__DIR__ . '/fixtures/phpcsstrict.json');
        $invalidLines = $phpcs->getLines();
        $expected = [
            '/full/path/to/file/src/XMLReport.php' => [
                11 => ['Opening brace should be on the line after the declaration; found 1 blank line(s)'],
                12 => ['Line indented incorrectly; expected at least 8 spaces, found 4'],
            ],
        ];

        $this->assertEquals($expected, $invalidLines);
        $this->assertFalse($phpcs->isValidLine('/full/path/to/file/src/XMLReport.php', 11));
        $this->assertTrue($phpcs->isValidLine('/full/path/to/file/src/XMLReport.php', 10));
    }
}
