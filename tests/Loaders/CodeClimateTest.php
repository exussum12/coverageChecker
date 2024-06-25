<?php
namespace exussum12\CoverageChecker\tests\Loaders;

use exussum12\CoverageChecker\Loaders\CodeClimate;
use PHPUnit\Framework\Attributes\Before;

class CodeClimateTest extends PhanTextTest
{
    /** @var  CodeClimate */
    protected $phan;
    protected $prefix = '';

    #[Before]
    protected function setUpTest()
    {
        $this->phan = new CodeClimate(__DIR__ . '/../fixtures/codeclimate.json');
    }
}
