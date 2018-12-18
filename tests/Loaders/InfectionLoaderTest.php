<?php
namespace exussum12\CoverageChecker\tests\Loaders;

use PHPUnit\Framework\TestCase;
use exussum12\CoverageChecker\Loaders\Infection;

class InfectionLoaderTest extends TestCase
{

    public function testCanParseFile()
    {

        $infection = new Infection(__DIR__ . '/../fixtures/infection-log.txt');
        $files = $infection->parseLines();

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

        $this->assertSame(
            array_values($files),
            $files
        );
    }

    public function testFileNotFound()
    {
        $infection = new Infection(__DIR__ . '/../fixtures/infection-log.txt');
        $this->assertTrue($infection->handleNotFoundFile());
    }
}
