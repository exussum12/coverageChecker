<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\TestCase;
use Exception;

/**
 * Ignored due to acceptance test needing to write values
 * @SuppressWarnings(PHPMD.Superglobals)
 */
class PhpunitDiffFilterTest extends TestCase
{
    use TestShim;
    public function testWrongArgs()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionCode(1);

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
        $this->assertContainsString('No lines found', $output);
    }

    public function testFailingBuild()
    {
        $GLOBALS['argv'] = [
            'diffFilter',
            '--phpunit',
            '--report=json',
            __DIR__ . '/fixtures/newFile.txt',
            __DIR__ . '/fixtures/coverage-change.xml',
            '70',
        ];
        try {
            ob_start();
            require(__DIR__ . '/../src/Runners/generic.php');
        } catch (Exception $e) {
            $output = ob_get_clean();
            $this->assertEquals(2, $e->getCode());
            $this->assertContainsString('66.67', $output);
            $this->assertContainsString('Failed', $output);
            return;
        }

        $this->fail("no exception thrown");
    }

    public function testPassingLowPercentage()
    {
        $GLOBALS['argv'] = [
            'diffFilter',
            '--phpunit',
            __DIR__ . '/fixtures/newFile.txt',
            __DIR__ . '/fixtures/coverage-change.xml',
            '60',
        ];

        ob_start();
        require(__DIR__ . '/../src/Runners/generic.php');
        $output = ob_get_clean();
        $this->assertContainsString('66.67%', $output);
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
        require(__DIR__ . '/../src/Runners/generic.php');
        $output = ob_get_clean();
        $this->assertContainsString('No lines found', $output);
    }
}
