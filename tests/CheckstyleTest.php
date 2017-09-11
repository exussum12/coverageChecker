<?php
namespace exussum12\CoverageChecker\tests;

use exussum12\CoverageChecker\CheckstyleLoader;

class CheckstyleTest extends PhanTextTest
{
    /** @var  CheckstyleLoader */
    protected $phan;
    protected $prefix = '';
    protected function setUp()
    {
        parent::setUp();
        $this->phan = new CheckstyleLoader(__DIR__ . '/fixtures/checkstyle.xml');
    }
}
