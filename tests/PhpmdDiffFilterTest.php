<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\TestCase;
use Exception;

class PhpmdDiffFilterTest extends TestCase
{

    public function testValid()
    {
        $GLOBALS['argv'] = [
            'phpunitDiffFilter',
            __DIR__ . '/fixtures/change.txt',
            __DIR__ . '/fixtures/phpmd.xml'
        ];
        ob_start();
        require(__DIR__ . "/../src/Runners/phpmdDiffFilter.php");
        $output = ob_get_clean();
        $this->assertContains('100.00%', $output);
    }

    public function testNoValidLines()
    {
        $GLOBALS['argv'] = [
            'phpunitDiffFilter',
            __DIR__ . '/fixtures/change.txt',
            __DIR__ . '/fixtures/phpmd-change.xml',
        ];
        try {
            ob_start();
            require(__DIR__ . "/../src/Runners/phpmdDiffFilter.php");
        } catch (Exception $e) {
            $output = ob_get_clean();
            $this->assertEquals(2, $e->getCode());
            $this->assertContains('0.00%', $output);
            return;
        }
        $this->fail("no exception thrown");
    }

    public function testNoValidLinesStrict()
    {
        $GLOBALS['argv'] = [
            'phpunitDiffFilter',
            __DIR__ . '/fixtures/change.txt',
            __DIR__ . '/fixtures/phpmd-change.xml',
            '--strict',
        ];
        try {
            ob_start();
            require(__DIR__ . "/../src/Runners/phpmdDiffFilter.php");
        } catch (Exception $e) {
            $output = ob_get_clean();
            $this->assertEquals(2, $e->getCode());
            $this->assertContains('0%', $output);
            return;
        }

        $this->fail("no exception thrown");
    }
}
