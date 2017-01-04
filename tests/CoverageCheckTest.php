<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\TestCase;

use exussum12\CoverageChecker\CoverageCheck;
use exussum12\CoverageChecker\DiffFileLoader;
use exussum12\CoverageChecker\FileMatchers;
use exussum12\CoverageChecker\XMLReport;

class CoverageCheckTest extends TestCase
{
    public function testCoverage()
    {
        $diffFileState = $this->createMock(DiffFileLoader::class);
        $diffFileState->method('getChangedLines')
            ->willReturn([
                'testFile1.php' => [1,2,3,4],
                'testFile2.php' => [3,4]

            ]);

        $xmlReport = $this->createMock(XMLReport::class);
        $xmlReport->method('getCoveredLines')
            ->willReturn([
                '/full/path/to/testFile1.php' => [1 => 1,2 => 0,3 => 1,4 => 1],
                '/full/path/to/testFile2.php' => [3 => 1,4 => 0]

            ]);

        $matcher = new FileMatchers\EndsWith;
        $coverageCheck = new CoverageCheck($diffFileState, $xmlReport, $matcher);
        $lines = $coverageCheck->getCoveredLines();
        $uncoveredLines = [
            'testFile1.php' => [2],
            'testFile2.php' => [4]
        ];
        $coveredLines = [
            'testFile1.php' => [1,3,4],
            'testFile2.php' => [3],
        ];

        $expected = [
            'coveredLines' => $coveredLines,
            'uncoveredLines' => $uncoveredLines,
        ];

        $this->assertEquals($expected, $lines);
    }

    public function testCoverageFailed()
    {
        $diffFileState = $this->createMock(DiffFileLoader::class);
        $diffFileState->method('getChangedLines')
            ->willReturn([
                'testFile1.php' => [1,2,3,4],
                'testFile2.php' => [3,4],

            ]);

        $xmlReport = $this->createMock(XMLReport::class);
        $xmlReport->method('getCoveredLines')
            ->willReturn([
                '/full/path/to/testFile1.php' => [1 => 1,2 => 0,3 => 1,4 => 1],

            ]);

        $matcher = new FileMatchers\EndsWith;
        $coverageCheck = new CoverageCheck($diffFileState, $xmlReport, $matcher);
        $lines = $coverageCheck->getCoveredLines();

        $uncoveredLines = [
            'testFile1.php' => [2],
        ];
        $coveredLines = [
            'testFile1.php' => [1,3,4],
        ];

        $expected = [
            'coveredLines' => $coveredLines,
            'uncoveredLines' => $uncoveredLines,
        ];

        $this->assertEquals($expected, $lines);
    }
}
