<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\TestCase;
use exussum12\CoverageChecker\CoverageCheck;
use exussum12\CoverageChecker\DiffFileLoader;
use exussum12\CoverageChecker\FileMatchers;
use exussum12\CoverageChecker\Loaders\Clover;

class CoverageCheckTest extends TestCase
{
    private $errorMessage = ['No Cover'];
    public function testCoverage()
    {
        $diffFileState = $this->createMock(DiffFileLoader::class);
        $diffFileState->method('getChangedLines')
            ->willReturn([
                'testFile1.php' => [1, 2, 3, 4],
                'testFile2.php' => [3, 4]

            ]);

        $xmlReport = $this->createMock(Clover::class);
        $xmlReport->method('parseLines')
            ->willReturn([
                '/full/path/to/testFile1.php',
                '/full/path/to/testFile2.php',
            ]);

        $xmlReport->method('getErrorsOnLine')
            ->will(
                $this->returnCallback(
                    function () {
                        $file = func_get_arg(0);
                        $line = func_get_arg(1);

                        if ($file == '/full/path/to/testFile1.php' && $line == 2) {
                            return $this->errorMessage;
                        }
                        if ($file == '/full/path/to/testFile2.php' && $line == 4) {
                            return $this->errorMessage;
                        }

                        return [];
                    }
                )
            );

        $matcher = new FileMatchers\EndsWith;
        $coverageCheck = new CoverageCheck($diffFileState, $xmlReport, $matcher);
        $lines = $coverageCheck->getCoveredLines();
        $uncoveredLines = [
            'testFile1.php' => [2 => $this->errorMessage],
            'testFile2.php' => [4 => $this->errorMessage],
        ];
        $coveredLines = [
            'testFile1.php' => [1, 3, 4],
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
                'testFile1.php' => [1, 2, 3, 4],
                'testFile2.php' => [3, 4],

            ]);

        $xmlReport = $this->createMock(Clover::class);
        $xmlReport->method('parseLines')
            ->willReturn([
                '/full/path/to/testFile1.php',

            ]);

        $xmlReport->method('handleNotFoundFile')
            ->willReturn(null);

        $xmlReport->method('getErrorsOnLine')
            ->will(
                $this->returnCallback(
                    function () {
                        $file = func_get_arg(0);
                        $line = func_get_arg(1);

                        if ($file == '/full/path/to/testFile1.php' && $line == 2) {
                            return $this->errorMessage;
                        }

                        return [];
                    }
                )
            );

        $matcher = new FileMatchers\EndsWith;
        $coverageCheck = new CoverageCheck($diffFileState, $xmlReport, $matcher);
        $lines = $coverageCheck->getCoveredLines();

        $uncoveredLines = [
            'testFile1.php' => [2 => $this->errorMessage],
        ];
        $coveredLines = [
            'testFile1.php' => [1, 3, 4],
        ];

        $expected = [
            'coveredLines' => $coveredLines,
            'uncoveredLines' => $uncoveredLines,
        ];

        $this->assertEquals($expected, $lines);
    }

    public function testAddingAllUnknownsCovered()
    {
        $diffFileState = $this->createMock(DiffFileLoader::class);
        $diffFileState->method('getChangedLines')
            ->willReturn([
                'testFile1.php' => [1, 2, 3, 4],
                'testFile2.php' => [3, 4],

            ]);

        $xmlReport = $this->createMock(Clover::class);
        $xmlReport->method('parseLines')
            ->willReturn([
                '/full/path/to/testFile1.php',
            ]);

        $xmlReport->method('handleNotFoundFile')
            ->willReturn(true);

        $xmlReport->method('getErrorsOnLine')
            ->will(
                $this->returnCallback(
                    function () {
                        $file = func_get_arg(0);
                        $line = func_get_arg(1);

                        if ($file == '/full/path/to/testFile1.php' && $line == 2) {
                            return $this->errorMessage;
                        }

                        return [];
                    }
                )
            );

        $matcher = new FileMatchers\EndsWith;
        $coverageCheck = new CoverageCheck($diffFileState, $xmlReport, $matcher);
        $lines = $coverageCheck->getCoveredLines();

        $uncoveredLines = [
            'testFile1.php' => [2 => $this->errorMessage],
        ];
        $coveredLines = [
            'testFile1.php' => [1, 3, 4],
            'testFile2.php' => [3, 4],
        ];

        $expected = [
            'coveredLines' => $coveredLines,
            'uncoveredLines' => $uncoveredLines,
        ];

        $this->assertEquals($expected, $lines);
    }

    public function testAddingAllUnknownsUnCovered()
    {
        $diffFileState = $this->createMock(DiffFileLoader::class);
        $diffFileState->method('getChangedLines')
            ->willReturn([
                'testFile1.php' => [1, 2, 3, 4],
                'testFile2.php' => [3, 4],

            ]);

        $xmlReport = $this->createMock(Clover::class);
        $xmlReport->method('parseLines')
            ->willReturn([
                '/full/path/to/testFile1.php',
            ]);

        $xmlReport->method('handleNotFoundFile')
            ->willReturn(false);

        $xmlReport->method('getErrorsOnLine')
            ->will(
                $this->returnCallback(
                    function () {
                        $file = func_get_arg(0);
                        $line = func_get_arg(1);

                        if ($file == '/full/path/to/testFile1.php' && $line == 2) {
                            return $this->errorMessage;
                        }

                        return [];
                    }
                )
            );

        $matcher = new FileMatchers\EndsWith;
        $coverageCheck = new CoverageCheck($diffFileState, $xmlReport, $matcher);
        $lines = $coverageCheck->getCoveredLines();

        $uncoveredLines = [
            'testFile1.php' => [2 => $this->errorMessage],
            'testFile2.php' => [
                3 => ['No Cover'],
                4 => ['No Cover'],
            ],
        ];
        $coveredLines = [
            'testFile1.php' => [1, 3, 4],
        ];

        $expected = [
            'coveredLines' => $coveredLines,
            'uncoveredLines' => $uncoveredLines,
        ];

        $this->assertEquals($expected, $lines);
    }

    public function testCoverageForContextLines()
    {
        $diffFileState = $this->createMock(DiffFileLoader::class);
        $diffFileState->method('getChangedLines')
            ->willReturn([
                'testFile1.php' => [1, 2, 4],

            ]);

        $xmlReport = $this->createMock(Clover::class);
        $xmlReport->method('parseLines')
            ->willReturn([
                '/full/path/to/testFile1.php'

            ]);

        $xmlReport->method('handleNotFoundFile')
            ->willReturn(false);

        $xmlReport->method('getErrorsOnLine')
            ->will(
                $this->returnCallback(
                    function () {
                        $file = func_get_arg(0);
                        $line = func_get_arg(1);

                        if ($file == '/full/path/to/testFile1.php' && $line == 2) {
                            return null;
                        }

                        return [];
                    }
                )
            );

        $matcher = new FileMatchers\EndsWith;
        $coverageCheck = new CoverageCheck($diffFileState, $xmlReport, $matcher);
        $lines = $coverageCheck->getCoveredLines();

        $coveredLines = [
            'testFile1.php' => [1, 4],
        ];

        $expected = [
            'coveredLines' => $coveredLines,
            'uncoveredLines' => [],
        ];

        $this->assertEquals($expected, $lines);
    }
}
