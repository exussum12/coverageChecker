<?php
namespace exussum12\CoverageChecker\tests\Loaders;

use PHPUnit\Framework\TestCase;
use exussum12\CoverageChecker\Loaders\PhpMd;

class LoadPhpMdReportTest extends TestCase
{
    public function testClassCanLoad()
    {
        $phpmd = new PhpMd(__DIR__ . '/../fixtures/phpmd.xml');
        $lines = $phpmd->parseLines();
        $file = '/full/path/to/file/src/CoverageCheck.php';
        $expected = [$file];
        $expectedError = [
            'The method addUnCoveredLine has a boolean flag argument ' .
            '$message, which is a certain sign of a ' .
            'Single Responsibility Principle violation.'
        ];

        $this->assertEquals($expected, $lines);

        $this->assertEquals(
            $expectedError,
            $phpmd->getErrorsOnLine($file, 57)
        );
        //second should respond as no errors as the first range reported it
        $this->assertEquals(
            [],
            $phpmd->getErrorsOnLine($file, 58)
        );

        $this->assertEquals(
            [],
            $phpmd->getErrorsOnLine($file, 10)
        );
    }

    public function testCorrectMissingFile()
    {
        $phpmd = new PhpMd(__DIR__ . '/fixtures/phpmd.xml');

        $this->assertTrue($phpmd->handleNotFoundFile());
    }
}
