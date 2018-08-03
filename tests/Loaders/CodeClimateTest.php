<?php
namespace exussum12\CoverageChecker\tests\Loaders;

use exussum12\CoverageChecker\Loaders\CodeClimate;

class CodeClimateTest extends PhanTextTest
{
    /** @var  CodeClimate */
    protected $phan;
    protected $prefix = '';
    protected function setUp()
    {
        parent::setUp();
        $this->phan = new CodeClimate(__DIR__ . '/../fixtures/codeclimate.json');
    }
}
