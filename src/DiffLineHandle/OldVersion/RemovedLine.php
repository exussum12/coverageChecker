<?php
namespace exussum12\CoverageChecker\DiffLineHandle\OldVersion;

use exussum12\CoverageChecker\DiffLineHandle;

class RemovedLine extends DiffLineHandle
{

    public function handle(string $line)
    {
        $this->diffFileState->addChangeLine();
    }

    public function isValid(string $line): bool
    {
        return $line[0] == '-';
    }
}
