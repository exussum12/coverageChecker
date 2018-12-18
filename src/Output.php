<?php
namespace exussum12\CoverageChecker;

interface Output
{
    public function output(array $coverage, float $percent, float $minimumPercent);
}
