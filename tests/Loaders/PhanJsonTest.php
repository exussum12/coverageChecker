<?php
namespace exussum12\CoverageChecker\tests\Loaders;

use exussum12\CoverageChecker\Loaders\PhanJson;
use PHPUnit\Framework\Attributes\Before;

class PhanJsonTest extends PhanTextTest
{
    /** @var  PhanJsonTest */
    protected $phan;

    #[Before]
    protected function setUpTest()
    {
        $this->phan = new PhanJson(__DIR__ . '/../fixtures/phan.json');
    }
}
