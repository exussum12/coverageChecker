<?php
namespace exussum12\CoverageChecker\tests\FileMatchers;

use PHPUnit\Framework\TestCase;
use exussum12\CoverageChecker\Exceptions\FileNotFound;
use exussum12\CoverageChecker\FileMatchers\Prefix;

class PrefixTest extends TestCase
{
    public function testPrefix()
    {
        $prefixMatcher = new Prefix('/full/path/to/');
        $needle = "file.php";
        $haystack = [
            '/full/path/to/someOtherFile.php',
            '/full/path/to/longer/file.php',
            '/full/path/to/file.php',
        ];

        $this->assertEquals(
            '/full/path/to/file.php',
            $prefixMatcher->match($needle, $haystack)
        );
    }

    public function testDoesNotExist()
    {
        $this->expectException(FileNotFound::class);
        $prefixMatcher = new Prefix('/full/path/to/');
        $needle = "fileDoesNotExist.php";
        $haystack = [];

        $prefixMatcher->match($needle, $haystack);
    }
}
