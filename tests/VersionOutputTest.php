<?php
namespace exussum12\CoverageChecker\tests;

use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Ignored due to acceptance test needing to write values
 * @SuppressWarnings(PHPMD.Superglobals)
 */
class VersionOutputTest extends TestCase
{

    public function testValid()
    {
        $this->expectException(Exception::class);
        $GLOBALS['argv'] = [
            'diffFilter',
            '-v',
        ];
        require(__DIR__ . "/../src/Runners/generic.php");
    }
}
