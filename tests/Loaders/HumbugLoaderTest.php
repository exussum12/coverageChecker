<?php
namespace exussum12\CoverageChecker\tests\Loaders;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use exussum12\CoverageChecker\Loaders\Humbug;

class HumbugLoaderTest extends TestCase
{
    public function testCanMakeClass()
    {
        $humbug = new Humbug(__DIR__ . '/../fixtures/humbug.json');
        $invalidFiles = $humbug->parseLines();

        $this->assertEquals(1, count($invalidFiles));
        $file = 'src/DiffLineHandle/OldVersion/DiffStart.php';

        $this->assertContains(
            'Failed on escaped check',
            current($humbug->getErrorsOnLine($file, 23))
        );
        $this->assertEquals(
            [],
            $humbug->getErrorsOnLine($file, 22)
        );
    }

    public function testHandleFileNotFound()
    {
        $humbug = new Humbug(__DIR__ . '/../fixtures/humbug.json');
        $this->assertTrue($humbug->handleNotFoundFile());
    }

    public function testBadFormat()
    {
        $this->expectException(InvalidArgumentException::class);
        new Humbug(__DIR__ . '/../fixtures/change.txt');
    }
}
