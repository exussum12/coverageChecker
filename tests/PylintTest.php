<?php
namespace exussum12\CoverageChecker\tests;

use exussum12\CoverageChecker\PylintLoader;

class PylintTest extends PhanTextTest
{
    /** @var  PylintLoader */
    protected $phan;
    protected function setUp()
    {
        parent::setUp();
        $this->phan = new PylintLoader(__DIR__ . '/fixtures/pylint.txt');
    }
}
