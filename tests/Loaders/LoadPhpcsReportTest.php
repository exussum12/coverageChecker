<?php
namespace exussum12\CoverageChecker\tests\Loaders;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use exussum12\CoverageChecker\Loaders\PhpCs;
use exussum12\CoverageChecker\Loaders\PhpCsStrict;

class LoadPhpcsReportTest extends TestCase
{
    public function testCanMakeClass()
    {
        $phpcs = new PhpCs(__DIR__ . '/../fixtures/phpcs.json');
        $phpcs->parseLines();

        $this->assertEquals(
            ['Opening brace should be on the line after the declaration; found 1 blank line(s)'],
            $phpcs->getErrorsOnLine('/full/path/to/file/src/XMLReport.php', 11)
        );
        $this->assertEquals(
            [],
            $phpcs->getErrorsOnLine('/full/path/to/file/src/XMLReport.php', 10)
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testRejectsInvalidData()
    {
        $this->expectException(InvalidArgumentException::class);
        new PhpCs(__DIR__ . '/../fixtures/change.txt');
    }

    public function testCorrectMissingFile()
    {
        $phpcs = new PhpCs(__DIR__ . '/../fixtures/phpcs.json');

        $this->assertTrue($phpcs->handleNotFoundFile());
    }

    public function testStrictMode()
    {
        $phpcs = new PhpCsStrict(__DIR__ . '/../fixtures/phpcsstrict.json');
        $phpcs->parseLines();

        $this->assertEquals(
            ['Opening brace should be on the line after the declaration; found 1 blank line(s)'],
            $phpcs->getErrorsOnLine('/full/path/to/file/src/XMLReport.php', 11)
        );

        $this->assertEquals(
            [],
            $phpcs->getErrorsOnLine('/full/path/to/file/src/XMLReport.php', 10)
        );
    }

    public function testWholeFileError()
    {
        $phpcs = new PhpCsStrict(__DIR__ . '/../fixtures/wholeFileErrorPhpcs.json');
        $phpcs->parseLines();

        $this->assertEquals(
            [
                'A file should declare new symbols (classes, functions, constants, etc.) and cause no other side' .
                ' effects, or it should execute logic with side effects, but should not do both. The first symbol is' .
                ' defined on line 2 and the first side effect is on line 7.',

                'End of line character is invalid; expected "\n" but found "\r\n"',
            ],
            $phpcs->getErrorsOnLine('/tmp/test/test.php', 100)
        );
    }
}
