<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\TestCase;

use exussum12\CoverageChecker\XMLReport;

class LoadXMLReportTest extends TestCase
{
    public function testLoadXML()
    {
        $xmlReport = new XMLReport(__DIR__ . '/fixtures/coverage.xml');
        $coveredLines = $xmlReport->getLines();
        $expected = [
            '/path/to/file/changedFile.php' => [
                10 => 4,
                11 => 4,
                14 => 0,
                15 => 3,
                18 => 3,
                19 => 3,
                22 => 3,
            ],
            '/path/to/file/otherFile.php' => [
                9 => 4,
                10 => 4,
            ],
        ];
        $this->assertEquals($expected, $coveredLines);
        $this->assertFalse($xmlReport->isValidLine('/path/to/file/changedFile.php', 14));
        $this->assertTrue($xmlReport->isValidLine('/path/to/file/changedFile.php', 10));
        //True as the report doesnt contain the file
        $this->assertTrue($xmlReport->isValidLine('/path/to/file/NonExistantFile.php', 6));
    }
}
