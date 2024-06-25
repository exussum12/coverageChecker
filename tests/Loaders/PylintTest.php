<?php
namespace exussum12\CoverageChecker\tests\Loaders;

use exussum12\CoverageChecker\Loaders\Pylint;
use PHPUnit\Framework\Attributes\Before;

class PylintTest extends PhanTextTest
{
    /** @var  Pylint */
    protected $phan;

    #[Before]
    protected function setUpTest(): void
    {
        $this->phan = new Pylint(__DIR__ . '/../fixtures/pylint.txt');
    }
}
