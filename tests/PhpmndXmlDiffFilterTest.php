<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\TestCase;
use exussum12\CoverageChecker\PhpMndXmlLoader;

class PhpmndXmlDiffFilterTest extends TestCase
{

    public function testValidFiles()
    {
        $file = __DIR__ . "/fixtures/phpmnd.xml";
        $mnd = new PhpMndXmlLoader($file);
        $files = $mnd->parseLines();
        $expected = [
            'bin/test/test.php',
            'bin/test/test2.php',
            'tests/files/test_1.php',
        ];

        $this->assertSame($expected, $files);
    }

    public function testShowsErrorOnLine()
    {
        $file = __DIR__ . "/fixtures/phpmnd.xml";
        $mnd = new PhpMndXmlLoader($file);
        $mnd->parseLines();

        $this->assertNotEmpty(
            $mnd->getErrorsOnLine('bin/test/test.php', 3)
        );
        $this->assertEmpty(
            $mnd->getErrorsOnLine('bin/test/test.php', 1)
        );
    }
}
