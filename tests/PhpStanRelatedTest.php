<?php
namespace exussum12\CoverageChecker\tests;

use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Ignored due to acceptance test needing to write values
 * @SuppressWarnings(PHPMD.Superglobals)
 */
class PhpStanRelatedTest extends TestCase
{
    public function testRelatedMethodsWithoutAutoload()
    {
        $GLOBALS['argv'] = [
            'diffFilter',
            '--phpstan',
            __DIR__ . '/fixtures/addTypeError.txt',
            __DIR__ . '/fixtures/phpstanTypeError.txt'
        ];

        ob_start();
        require(__DIR__ . "/../src/Runners/generic.php");
        $output = ob_get_clean();
        $this->assertContains('100.00%', $output);
    }

    public function testRelatedMethods()
    {
        $GLOBALS['argv'] = [
            'diffFilter',
            '--phpstan',
            '--autoload=' . __DIR__ . '/fixtures/phpstanTypeError.php',
            __DIR__ . '/fixtures/addTypeError.txt',
            __DIR__ . '/fixtures/phpstanTypeError.txt'
        ];

        try {
            ob_start();
            require(__DIR__ . "/../src/Runners/generic.php");
        } catch (Exception $exception) {
            $output = ob_get_clean();
            $this->assertContains('used test.php', $output);
            return true;
        }

        $this->fail('Exception not thrown when Expected');
    }
}
