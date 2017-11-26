<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\TestCase;
use Exception;

/**
 * Ignored due to acceptance test needing to write values
 * @SuppressWarnings(PHPMD.Superglobals)
 */
class PhpmndDiffFilterTest extends TestCase
{

    public function testValid()
    {
        $GLOBALS['argv'] = [
            'phpunitDiffFilter',
            '--phpmnd',
            __DIR__ . '/fixtures/change.txt',
            __DIR__ . '/fixtures/phpmnd.txt'
        ];
        ob_start();
        require(__DIR__ . "/../src/Runners/generic.php");
        $output = ob_get_clean();
        $this->assertContains('100.00%', $output);
    }
}
