<?php
namespace exussum12\CoverageChecker\DiffLineHandle;

use exussum12\CoverageChecker\DiffLineHandle;

class DiffStart extends DiffLineHandle
{

    public function handle($line)
    {
        $foundVariables = sscanf(
            $line,
            '@@ -%d,%d +%d,%d @@'
        );

        $newFrom = $foundVariables[2];

        $this->diffFileState->setCurrentPosition($newFrom - 1);
    }

    public function isValid($line)
    {
        return $line[0] == '@' && $line[1] == '@';
    }
}
