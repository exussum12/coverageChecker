<?php
namespace exussum12\CoverageChecker\tests;

use exussum12\CoverageChecker\InfectionLoader;
use PHPUnit\Framework\TestCase;

class InfectionLoaderTest extends TestCase
{

    public function testCanParseFile()
    {

        $infection = new InfectionLoader(__DIR__ . '/fixtures/infection-log.txt');
        $infection->parseLines();
        $file = '/home/scott/code/coverageChecker/src/DiffFilter.php';

        $this->assertFalse(
            (bool)$infection->getErrorsOnLine(
                $file,
                20
            )
        );
        $this->assertTrue(
            (bool)$infection->getErrorsOnLine(
                $file,
                21
            )
        );
    }

    public function testFileNotFound()
    {
        $infection = new InfectionLoader(__DIR__ . '/fixtures/infection-log.txt');
        $this->assertTrue($infection->handleNotFoundFile());
    }
}
