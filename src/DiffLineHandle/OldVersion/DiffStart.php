<?php
namespace exussum12\CoverageChecker\DiffLineHandle\OldVersion;

use exussum12\CoverageChecker\DiffLineHandle;

class DiffStart extends DiffLineHandle
{

    public function handle(string $line)
    {
        $foundVariables = sscanf(
            $line,
            '@@ -%d,%d +%d,%d @@'
        );

        $oldFrom = $foundVariables[0];

        $this->diffFileState->setCurrentPosition($oldFrom - 1);
    }

    public function isValid(string $line): bool
    {
        return $line[0] == '@' && $line[1] == '@';
    }
}
