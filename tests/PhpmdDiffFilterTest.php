<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\TestCase;
use Exception;

/**
 * Ignored due to acceptance test needing to write values
 * @SuppressWarnings(PHPMD.Superglobals)
 */
class PhpmdDiffFilterTest extends TestCase
{

    public function testValid()
    {
        $GLOBALS['argv'] = [
            'diffFilter',
            '--phpmd',
            '--report=json',
            __DIR__ . '/fixtures/change.txt',
            __DIR__ . '/fixtures/phpmd.xml'
        ];
        ob_start();
        require(__DIR__ . "/../src/Runners/generic.php");
        $output = ob_get_clean();
        $this->assertStringContainsString('100.00', $output);
        $this->assertStringContainsString('Passed', $output);
    }

    public function testNoValidLines()
    {
        $GLOBALS['argv'] = [
            'diffFilter',
            '--phpmd',
            __DIR__ . '/fixtures/change.txt',
            __DIR__ . '/fixtures/phpmd-change.xml',
        ];
        try {
            ob_start();
            require(__DIR__ . '/../src/Runners/generic.php');
        } catch (Exception $e) {
            $output = ob_get_clean();
            $this->assertEquals(2, $e->getCode());
            $this->assertStringContainsString('0.00%', $output);
            return;
        }
        $this->fail("no exception thrown");
    }

    public function testNoValidLinesStrict()
    {
        $GLOBALS['argv'] = [
            'diffFilter',
            '--phpmdStrict',
            __DIR__ . '/fixtures/change.txt',
            __DIR__ . '/fixtures/phpmd-change.xml',
        ];
        try {
            ob_start();
            require(__DIR__ . "/../src/Runners/generic.php");
        } catch (Exception $e) {
            $output = ob_get_clean();
            $this->assertEquals(2, $e->getCode());
            $this->assertStringContainsString('0%', $output);
            return;
        }

        $this->fail('no exception thrown');
    }
}
