<?php
namespace exussum12\CoverageChecker\tests\Loaders;

use exussum12\CoverageChecker\Loaders\Checkstyle;

class CheckstyleTest extends PhanTextTest
{
    /** @var  Checkstyle */
    protected $phan;
    protected $prefix = '';

    /**
     * @before
     */
    protected function setUpTest()
    {
        $this->phan = new Checkstyle(__DIR__ . '/../fixtures/checkstyle.xml');
    }
}
