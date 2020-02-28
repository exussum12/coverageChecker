<?php
namespace exussum12\CoverageChecker\tests\Loaders;

use exussum12\CoverageChecker\Loaders\Pylint;

class PylintTest extends PhanTextTest
{
    /** @var  Pylint */
    protected $phan;

    /**
     * @before
     */
    protected function setUpTest()
    {
        $this->phan = new Pylint(__DIR__ . '/../fixtures/pylint.txt');
    }
}
