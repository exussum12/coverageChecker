<?php
namespace exussum12\CoverageChecker\DiffLineHandle;

use exussum12\CoverageChecker\DiffLineHandle;

class NewFile extends DiffLineHandle
{

    public function handle($line)
    {
        $parsedLine = sscanf($line, '+++ %1s/%s');
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
