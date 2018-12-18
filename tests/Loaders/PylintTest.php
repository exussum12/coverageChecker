<?php
namespace exussum12\CoverageChecker\tests\Loaders;

use exussum12\CoverageChecker\Loaders\Pylint;

class PylintTest extends PhanTextTest
{
    /** @var  Pylint */
    protected $phan;
    protected function setUp()
    {
        parent::setUp();
        $this->phan = new Pylint(__DIR__ . '/../fixtures/pylint.txt');
    }
}
