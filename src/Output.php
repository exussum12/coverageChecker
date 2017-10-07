<?php
namespace exussum12\CoverageChecker;

interface Output
{
    public function output($coverage, $percent, $minimumPercent);
}
