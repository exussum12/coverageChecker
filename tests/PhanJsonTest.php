<?php
namespace exussum12\CoverageChecker\tests;

use exussum12\CoverageChecker\PhanJsonLoader;

class PhanJsonTest extends PhanTextTest
{
    /** @var  PhanJsonTest */
    protected $phan;
    protected function setUp()
    {
        parent::setUp();
        $this->phan = new PhanJsonLoader(__DIR__ . '/fixtures/phan.json');
    }
}
