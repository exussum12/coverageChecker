<?php
namespace exussum12\CoverageChecker\DiffLineHandle;

use exussum12\CoverageChecker\DiffLineHandle;

class NewFile extends DiffLineHandle
{

    public function handle($line)
    {
        sscanf($line, '+++ %1s/%s', $prefix, $currentFileName);
        if ($currentFileName) {
            $this->diffFileState->setCurrentFile($currentFileName);
        }
    }

    public static function isValid($line)
    {
        return $line[0] == '+' && $line[1] == '+';
    }
}
