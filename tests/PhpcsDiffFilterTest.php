<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\TestCase;

/**
 * Ignored due to acceptance test needing to write values
 * @SuppressWarnings(PHPMD.Superglobals)
 */
class PhpcsDiffFilterTest extends TestCase
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
        require(__DIR__ . '/../src/Runners/generic.php');
        $output = ob_get_clean();
        $this->assertStringContainsString('100.00%', $output);
    }

    public function testStrictMode()
    {
        $GLOBALS['argv'] = [
            'diffFilter',
            '--phpcs',
            __DIR__ . '/fixtures/change.txt',
            __DIR__ . '/fixtures/phpcsstrict.json'
        ];
        ob_start();
        require(__DIR__ . '/../src/Runners/generic.php');
        $output = ob_get_clean();
        $this->assertStringContainsString('100.00%', $output);
    }
}
