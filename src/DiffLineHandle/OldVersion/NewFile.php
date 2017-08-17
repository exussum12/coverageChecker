<?php
namespace exussum12\CoverageChecker\DiffLineHandle\OldVersion;

use exussum12\CoverageChecker\DiffLineHandle;

class NewFile extends DiffLineHandle
{

    public function handle($line)
    {
        if (preg_match('#--- a?/(?<filename>.*)#', $line, $match)) {
            $this->diffFileState->setCurrentFile($match['filename']);
        }
    }

    public function isValid($line)
    {
        return $line[0] == '-' && $line[1] == '-';
    }
}
