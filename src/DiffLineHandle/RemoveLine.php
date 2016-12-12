<?php
namespace exussum12\CoverageChecker\DiffLineHandle;

use exussum12\CoverageChecker\DiffLineHandle;

class RemovedLine extends DiffLineHandle
{

    public function handle($line)
    {
        $this->diffFileState->decrementCurrentPosition();
    }

    public static function isValid($line)
    {
        return $line[0] == '-';
    }
}
