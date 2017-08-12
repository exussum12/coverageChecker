<?php
namespace exussum12\CoverageChecker\DiffLineHandle\NewVersion;

use exussum12\CoverageChecker\DiffLineHandle;

class AddedLine extends DiffLineHandle
{

    public function handle($line)
    {
        $this->diffFileState->addChangeLine();
    }

    public function isValid($line)
    {
        return $line[0] == '+';
    }
}
