<?php
namespace exussum12\CoverageChecker\DiffLineHandle\OldVersion;

use exussum12\CoverageChecker\DiffLineHandle;

class RemovedLine extends DiffLineHandle
{

    public function handle($line)
    {
        $this->diffFileState->addChangeLine();
    }

    public function isValid($line)
    {
        return $line[0] == '-';
    }
}
