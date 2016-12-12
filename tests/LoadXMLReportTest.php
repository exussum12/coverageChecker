<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\TestCase;

use exussum12\CoverageChecker\XMLReport;

class LoadXMLReportTest extends TestCase
{
    public function testLoadXML()
    {
        $fileLoader = new XMLReport(__DIR__ . '/fixtures/coverage.xml');
        $coveredLines = $fileLoader->getCoveredLines();
        $expected = [
            '/path/to/file/changedFile.php' => [
                10 => 4,
                11 => 4,
                14 => 3,
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
    }
}
