<?php
namespace exussum12\CoverageChecker\DiffLineHandle\NewVersion;

use exussum12\CoverageChecker\DiffLineHandle;

class NewFile extends DiffLineHandle
{

    public function handle($line)
    {
        $parsedLine = sscanf($line, '+++ %1s/%s');

        if (empty($parsedLine[1])) {
            return;
        }

        $currentFileName = $parsedLine[1];
        if ($currentFileName) {
            $this->diffFileState->setCurrentFile($currentFileName);
        }
    }

    public function isValid($line)
    {
        return $line[0] == '+' && $line[1] == '+';
    }
}
