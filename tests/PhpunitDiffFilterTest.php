<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\TestCase;
use Exception;

class PhpunitDiffFilterTest extends TestCase
{
    /**
     * @expectedException Exception
     * @expectedExceptionCode 1
     */
    public function testWrongArgs()
    {
        $GLOBALS['argv'] = [];
        require(__DIR__ . "/../src/Runners/generic.php");
    }

    public function testWorkingCorrectly()
    {
        $GLOBALS['argv'] = [
            'diffFilter',
            '--phpunit',
            __DIR__ . '/fixtures/change.txt',
            __DIR__ . '/fixtures/coverage.xml'
        ];
        ob_start();
        require(__DIR__ . "/../src/Runners/generic.php");
        $output = ob_get_clean();
        $this->assertContains('No lines found', $output);
    }

    public function testFailingBuild()
    {
        $GLOBALS['argv'] = [
            'diffFilter',
            '--phpunit',
            '--report=json',
            __DIR__ . '/fixtures/newFile.txt',
            __DIR__ . '/fixtures/coverage-change.xml',
            70
        ];
        try {
            ob_start();
            require(__DIR__ . "/../src/Runners/generic.php");
        } catch (Exception $e) {
            $output = ob_get_clean();
            $this->assertEquals(2, $e->getCode());
            $this->assertContains('66.67', $output);
            $this->assertContains('Failed', $output);
            return;
        }

        $this->fail("no exception thrown");
    }

    public function testPassingLowPercentage()
    {
        $GLOBALS['argv'] = [
            'diffFilter',
            '--phpunit',
            '--report=json',
            __DIR__ . '/fixtures/newFile.txt',
            __DIR__ . '/fixtures/coverage-change.xml',
            60
        ];

        ob_start();
        require(__DIR__ . "/../src/Runners/generic.php");
        $output = ob_get_clean();
        $this->assertContains('66.67', $output);
        $this->assertContains('Passed', $output);
    }

    public function testNoCoveredLines()
    {
        $GLOBALS['argv'] = [
            'diffFilter',
            '--phpunit',
            __DIR__ . '/fixtures/removeFile.txt',
            __DIR__ . '/fixtures/coverage-change.xml',
        ];

        ob_start();
        require(__DIR__ . "/../src/Runners/generic.php");
        $output = ob_get_clean();
        $this->assertContains('No lines found', $output);
    }
}
