<?php
namespace exussum12\CoverageChecker\tests;

use PHPUnit\Framework\TestCase;

class Output extends TestCase
{
    protected function getAllValid()
    {
        return [];
    }

    protected function getFailing()
    {
        return [
            'file1.php' => [
                10 => ['Line 10 has an error'],
            ],
            'file2.php' => [
                15 => ['Line 15 has another error'],
            ],
        ];
    }
}
