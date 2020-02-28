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
    use TestShim;
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
            $file = $output->violations->{'DocBlocks.php'};
            $this->assertEquals(
                14,
                count($file)
            );

            $this->assertEquals(
                5,
                count($file[12]->message)
            );

            return true;
        }

        $this->fail('Exception not thrown when Expected');
    }

    public function testRelatedMethodsFileNotFound()
    {
        $GLOBALS['argv'] = [
            'diffFilter',
            '--phpcs',
            '--report=json',
            __DIR__ . '/fixtures/DocBlocks.txt',
            __DIR__ . '/fixtures/DocBlocksNotFound.json'
        ];

        try {
            ob_start();
            require(__DIR__ . '/../src/Runners/generic.php');
        } catch (Exception $exception) {
            $output = ob_get_clean();
            $this->assertContainsString("Can't find file", $output);

            return true;
        }

        $this->fail('Exception not thrown when Expected');
    }
}
