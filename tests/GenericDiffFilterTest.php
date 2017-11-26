<?php
namespace exussum12\CoverageChecker\tests;

use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Ignored due to acceptance test needing to write values
 * @SuppressWarnings(PHPMD.Superglobals)
 */
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
        $GLOBALS['argv'] = [
            'diffFilter',
            __DIR__ . '/fixtures/change.txt',
            __DIR__ . '/fixtures/phpcs.json'
        ];
        try {
            ob_start();
            require(__DIR__ . "/../src/Runners/generic.php");
        } catch (Exception $exception) {
            $output = ob_get_clean();
            $this->assertContains('--phpcs', $output);
            return true;
        }

        $this->fail('Exception not thrown when Expected');
    }
}
