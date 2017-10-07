<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\TestCase;

use exussum12\CoverageChecker\CloverLoader;

class LoadCloverReportTest extends TestCase
{
    public function testLoadXML()
    {
        $xmlReport = new CloverLoader(__DIR__ . '/fixtures/coverage.xml');
        $coveredLines = $xmlReport->getLines();
        $expected = [
            '/path/to/file/changedFile.php' => [
                10 => true,
                11 => true,
                14 => 'No test coverage',
                15 => true,
                18 => true,
                19 => true,
                22 => true,
            ],
            '/path/to/file/otherFile.php' => [
                9 => true,
                10 => true,
            ],
        ];
        $this->assertEquals($expected, $coveredLines);
        $this->assertFalse($xmlReport->isValidLine('/path/to/file/changedFile.php', 14));
        $this->assertTrue($xmlReport->isValidLine('/path/to/file/changedFile.php', 10));
        //True as the report doesnt contain the file
        $this->assertNull($xmlReport->isValidLine('/path/to/file/NonExistantFile.php', 6));
    }

    public function testCorrectMissingFile()
    {
        $xmlReport = new CloverLoader(__DIR__ . '/fixtures/coverage.xml');

        $this->assertNull($xmlReport->handleNotFoundFile());
    }
}
