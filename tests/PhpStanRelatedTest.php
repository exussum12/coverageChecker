<?php
namespace exussum12\CoverageChecker\tests;

use Exception;
use PHPUnit\Framework\TestCase;

class PhpStanRelatedTest extends TestCase
{
    public function testRelatedMethods()
    {
        $this->expectException(Exception::class);
        $GLOBALS['argv'] = [
            'diffFilter',
            '--phpstan',
            '--autoload=' . __DIR__ . 'fixtures/phpstanTypeError.php',
            __DIR__ . '/fixtures/change.txt',
            __DIR__ . '/fixtures/phpstandTypeError.txt'
        ];

        require(__DIR__ . "/../src/Runners/generic.php");
    }
}
