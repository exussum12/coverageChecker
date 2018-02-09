<?php
namespace exussum12\CoverageChecker\tests;

use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Ignored due to acceptance test needing to write values
 * @SuppressWarnings(PHPMD.Superglobals)
 */
class PhpCsReflectionTest extends TestCase
{
    public function testRelatedMethods()
    {
        $GLOBALS['argv'] = [
            'diffFilter',
            '--phpcs',
            '--report=json',
            __DIR__ . '/fixtures/DocBlocks.txt',
            __DIR__ . '/fixtures/DocBlocks.json'
        ];

        try {
            ob_start();
            require(__DIR__ . "/../src/Runners/generic.php");
        } catch (Exception $exception) {
            $output = json_decode(ob_get_clean());
            $this->assertEquals(
                14,
                count($output->violations->{'DocBlocks.php'})
            );

            return true;
        }

        $this->fail('Exception not thrown when Expected');
    }
}
