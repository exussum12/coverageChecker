<?php
namespace exussum12\CoverageChecker\tests\Loaders;

use exussum12\CoverageChecker\Loaders\PhanJson;

class PhanJsonTest extends PhanTextTest
{
    /** @var  PhanJsonTest */
    protected $phan;

    /**
     * @before
     */
    protected function setUpTest()
    {
        $this->phan = new PhanJson(__DIR__ . '/../fixtures/phan.json');
    }
}
