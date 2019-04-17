<?php
namespace exussum12\CoverageChecker\tests\FileMatchers;

use PHPUnit\Framework\TestCase;
use exussum12\CoverageChecker\FileMatchers\EndsWith;

class EndsWithTest extends TestCase
{
    public function testPrefix()
    {
        $prefixMatcher = new EndsWith();
        $needle = 'windows\\file.php';
        $haystack = [
            '/full/path/to/windows/file.php',
        ];

        $this->assertEquals(
            '/full/path/to/windows/file.php',
            $prefixMatcher->match($needle, $haystack)
        );
    }
}
