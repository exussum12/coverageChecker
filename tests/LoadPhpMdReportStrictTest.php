<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\TestCase;
use exussum12\CoverageChecker\PhpMdLoaderStrict;

class LoadPhpMdReportStrictTest extends TestCase
{
    public function testClassCanLoad()
    {
        $phpmd = new PhpMdLoaderStrict(__DIR__ . '/fixtures/phpmd.xml');
        $lines = $phpmd->getLines();
        $file = '/full/path/to/file/src/CoverageCheck.php';
        $expected = [
           $file => [
                56 => [
                    'The method addUnCoveredLine has a boolean flag argument ' .
                    '$message, which is a certain sign of a ' .
                    'Single Responsibility Principle violation.'
                ],
                57 => [
                    'The method addUnCoveredLine has a boolean flag argument ' .
                    '$message, which is a certain sign of a ' .
                    'Single Responsibility Principle violation.'
                ],
                58 => [
                    'The method addUnCoveredLine has a boolean flag argument ' .
                    '$message, which is a certain sign of a ' .
                    'Single Responsibility Principle violation.'
                ],
            ],
        ];
        $this->assertEquals($expected, $lines);
        $this->assertFalse($phpmd->isValidLine($file, 57));
        $this->assertTrue($phpmd->isValidLine($file, 10));
    }

    public function testCorrectMissingFile()
    {
        $phpmd = new PhpMdLoaderStrict(__DIR__ . '/fixtures/phpmd.xml');

        $this->assertTrue($phpmd->handleNotFoundFile());
    }
}
