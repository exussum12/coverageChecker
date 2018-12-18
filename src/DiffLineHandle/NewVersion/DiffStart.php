<?php
namespace exussum12\CoverageChecker\DiffLineHandle\NewVersion;

use exussum12\CoverageChecker\DiffLineHandle;

class DiffStart extends DiffLineHandle
{
    public function handle(string $line)
    {
        $foundVariables = sscanf(
            $line,
            '@@ -%d,%d +%d,%d @@'
        );

        $newFrom = $foundVariables[2];

        $this->diffFileState->setCurrentPosition($newFrom - 1);
    }

    public function isValid(string $line): bool
    {
        return $line[0] == '@' && $line[1] == '@';
    }
}
