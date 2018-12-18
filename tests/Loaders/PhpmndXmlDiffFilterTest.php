<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\TestCase;
use exussum12\CoverageChecker\Loaders\PhpMndXml;

class PhpmndXmlDiffFilterTest extends TestCase
{
    /** @var PhpMndXmlLoader */
    private $mnd;

    public function setUp()
    {
        parent::setUp();
        $file = __DIR__ . "/../fixtures/phpmnd.xml";
        $this->mnd = new PhpMndXml($file);
    }

    public function testValidFiles()
    {
        $files = $this->mnd->parseLines();
        $expected = [
            'bin/test/test.php',
            'bin/test/test2.php',
            'tests/files/test_1.php',
        ];

        $this->assertSame($expected, $files);
    }

    public function testShowsErrorOnLine()
    {
        $this->mnd->parseLines();

        $this->assertNotEmpty(
            $this->mnd->getErrorsOnLine('bin/test/test.php', 3)
        );
        $this->assertEmpty(
            $this->mnd->getErrorsOnLine('bin/test/test.php', 1)
        );
    }

    public function testFileNotFound()
    {
        $this->assertTrue($this->mnd->handleNotFoundFile());
    }
}
