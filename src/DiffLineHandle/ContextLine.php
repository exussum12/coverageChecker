<?php
namespace exussum12\CoverageChecker\DiffLineHandle;

use exussum12\CoverageChecker\DiffLineHandle;

class ContextLine extends DiffLineHandle
{

    public function handle($line)
    {
        //no need to do anything, Its just context
    }

    public function isValid($line)
    {
        return true;
    }
}
