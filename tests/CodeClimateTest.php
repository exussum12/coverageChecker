<?php
namespace exussum12\CoverageChecker\tests;

use exussum12\CoverageChecker\CodeClimateLoader;

class CodeClimateTest extends PhanTextTest
{
    /** @var  CodeClimateLoader */
    protected $phan;
    protected $prefix = '';
    protected function setUp()
    {
        parent::setUp();
        $this->phan = new CodeClimateLoader(__DIR__ . '/fixtures/codeclimate.json');
    }
}
