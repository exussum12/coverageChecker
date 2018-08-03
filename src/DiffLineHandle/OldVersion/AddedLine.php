<?php
namespace exussum12\CoverageChecker\DiffLineHandle\OldVersion;

use exussum12\CoverageChecker\DiffLineHandle;

class AddedLine extends DiffLineHandle
{

    public function handle(string $line)
    {
        $this->diffFileState->decrementCurrentPosition();
        $this->diffFileState->addChangeLine();
    }

    public function isValid(string $line): bool
    {
        return $line[0] == '+' && $line[1] != "+";
    }
}
