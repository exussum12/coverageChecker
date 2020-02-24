<?php
namespace exussum12\CoverageChecker\DiffLineHandle\OldVersion;

use exussum12\CoverageChecker\DiffLineHandle;

class NewFile extends DiffLineHandle
{

    public function handle(string $line)
    {
        $match = [];
        if (preg_match('#--- a?/(?<filename>.*)#', $line, $match)) {
            $this->diffFileState->setCurrentFile($match['filename']);
        }
    }

    public function isValid(string $line): bool
    {
        return $line[0] == '-' && $line[1] == '-';
    }
}
