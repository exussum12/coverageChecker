<?php
namespace exussum12\CoverageChecker\DiffLineHandle;

use exussum12\CoverageChecker\DiffLineHandle;

class AddedLine extends DiffLineHandle
{

    public function handle($line)
    {
        $this->diffFileState->addChangeLine();
    }

    public static function isValid($line)
    {
        return $line[0] == '+';
    }
}
