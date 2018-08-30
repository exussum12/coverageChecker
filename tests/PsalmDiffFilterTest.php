<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\TestCase;
use Exception;

/**
 * Ignored due to acceptance test needing to write values
 * @SuppressWarnings(PHPMD.Superglobals)
 */
class PsalmDiffFilterTest extends TestCase
{

    public function testValid()
    {
        $GLOBALS['argv'] = [
            'diffFilter',
            '--psalm',
            __DIR__ . '/fixtures/change.txt',
            __DIR__ . '/fixtures/psalm.xml'
        ];
        ob_start();
        require(__DIR__ . "/../src/Runners/generic.php");
        $output = ob_get_clean();
        $this->assertContains('100.00% Covered', $output);
    }

    public function testNoValidLines()
    {
        $GLOBALS['argv'] = [
            'diffFilter',
            '--psalm',
            __DIR__ . '/fixtures/change.txt',
            __DIR__ . '/fixtures/psalm-change.xml',
        ];
        try {
            ob_start();
            require(__DIR__ . "/../src/Runners/generic.php");
        } catch (Exception $exception) {
            $output = ob_get_clean();
            $this->assertEquals(2, $exception->getCode());
            $this->assertContains('0.00%', $output);
            return;
        }
        $this->fail("no exception thrown");
    }
}
