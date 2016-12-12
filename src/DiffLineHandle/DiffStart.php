<?php
namespace exussum12\CoverageChecker\DiffLineHandle;

use exussum12\CoverageChecker\DiffLineHandle;

class DiffStart extends DiffLineHandle
{

    public function handle($line)
    {
        sscanf(
            $line,
            '@@ -%d,%d +%d,%d @@',
            $oldFrom,
            $oldTo,
            $newFrom,
            $newTo
        );

        $this->diffFileState->setCurrentPosition($newFrom -1);
    }

    public static function isValid($line)
    {
        return $line[0] == '@' && $line[1] == '@';
    }
}
