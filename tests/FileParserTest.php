<?php
namespace exussum12\CoverageChecker\tests;

use exussum12\CoverageChecker\FileParser;
use PHPUnit\Framework\TestCase;

class FileParserTest extends TestCase
{
    public function testNoResultFromAst()
    {
        $badCode = "<?php badCode";
        $fileParser = new FileParser($badCode);

        $this->assertSame([], $fileParser->getClassLimits());
    }
}
