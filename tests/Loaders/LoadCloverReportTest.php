<?php
namespace exussum12\CoverageChecker\tests\Loaders;

use PHPUnit\Framework\TestCase;
use exussum12\CoverageChecker\Loaders\Clover;

class LoadCloverReportTest extends TestCase
{
    public function testLoadXML()
    {
        $xmlReport = new Clover(__DIR__ . '/../fixtures/coverage.xml');
        $coveredLines = $xmlReport->parseLines();
        $expected = [
            '/path/to/file/changedFile.php',
            '/path/to/file/otherFile.php',
        ];
        $this->assertEquals($expected, $coveredLines);

        $this->assertEquals(
            ['No unit test covering this line'],
            $xmlReport->getErrorsOnLine('/path/to/file/changedFile.php', 14)
        );

        $this->assertEquals(
            [],
            $xmlReport->getErrorsOnLine('/path/to/file/changedFile.php', 10)
        );
        //True as the report doesnt contain the file
        $this->assertNull($xmlReport->getErrorsOnLine('/path/to/file/NonExistantFile.php', 6));
    }

    public function testCorrectMissingFile()
    {
        $xmlReport = new Clover(__DIR__ . '/fixtures/coverage.xml');

        $this->assertNull($xmlReport->handleNotFoundFile());
    }
}
