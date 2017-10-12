<?php
namespace exussum12\CoverageChecker\tests;

use exussum12\CoverageChecker\HumbugLoader;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class HumbugLoaderTest extends TestCase
{
    public function testCanMakeClass()
    {
        $humbug = new HumbugLoader(__DIR__ . '/fixtures/humbug.json');
        $invalidLines = $humbug->getLines();

        $this->assertEquals(1, count($invalidLines));
        $this->assertFalse($humbug->isValidLine('src/DiffLineHandle/OldVersion/DiffStart.php', 23));
        $this->assertTrue($humbug->isValidLine('src/DiffLineHandle/OldVersion/DiffStart.php', 22));
    }

    public function testHandleFileNotFound()
    {
        $humbug = new HumbugLoader(__DIR__ . '/fixtures/humbug.json');
        $this->assertTrue($humbug->handleNotFoundFile());
    }

    public function testBadFormat()
    {
        $this->expectException(InvalidArgumentException::class);
        new HumbugLoader(__DIR__ . '/fixtures/change.txt');
    }
}
