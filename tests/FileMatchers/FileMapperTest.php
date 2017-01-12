<?php
namespace exussum12\CoverageChecker\tests\FileMatchers;

use PHPUnit\Framework\TestCase;
use exussum12\CoverageChecker\Exceptions\FileNotFound;
use exussum12\CoverageChecker\FileMatchers\FileMapper;

class FileMapperTest extends TestCase
{
    public function testPrefix()
    {
        $fileMapper = new FileMapper('unwantedPrefix', '/home/person/code');
        $needle = "unwantedPrefix/file.php";
        $haystack = [
            '/home/person/code/someOtherFile.php',
            '/home/person/code/longer/file.php',
            '/home/person/code/file.php',

        ];

        $this->assertEquals(
            '/home/person/code/file.php',
            $fileMapper->match($needle, $haystack)
        );
    }

    public function testDoesNotExist()
    {
        $this->expectException(FileNotFound::class);
        $fileMapper = new FileMapper('prefix', '/full/path');
        $needle = "fileDoesNotExist.php";
        $haystack = [];

        $fileMapper->match($needle, $haystack);
    }
}
