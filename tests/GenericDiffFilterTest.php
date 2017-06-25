<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\TestCase;

class GenericDiffFilterTest extends TestCase
{

    public function testValid()
    {
        $GLOBALS['argv'] = [
            'diffFilter',
            '--phpcs',
            __DIR__ . '/fixtures/change.txt',
            __DIR__ . '/fixtures/phpcs.json'
        ];
        ob_start();
        require(__DIR__ . "/../src/Runners/generic.php");
        $output = ob_get_clean();
        $this->assertContains('100.00%', $output);
    }

    public function testMissingHandler()
    {
        $this->expectException("Exception");

        $GLOBALS['argv'] = [
            'diffFilter',
            __DIR__ . '/fixtures/change.txt',
            __DIR__ . '/fixtures/phpcs.json'
        ];
        require(__DIR__ . "/../src/Runners/generic.php");
    }
}
