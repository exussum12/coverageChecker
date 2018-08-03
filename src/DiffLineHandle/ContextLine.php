<?php
namespace exussum12\CoverageChecker\DiffLineHandle;

use exussum12\CoverageChecker\DiffLineHandle;

class ContextLine extends DiffLineHandle
{
    public function handle(string $line)
    {
        //no need to do anything, It's just context
    }

    public function isValid(string $line): bool
    {
        return true;
    }
}
