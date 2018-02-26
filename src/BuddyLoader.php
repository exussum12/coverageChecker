<?php
namespace exussum12\CoverageChecker;

/**
 * Class BuddyLoader
 * Used for parsing magic number reports from buddy
 * @package exussum12\CoverageChecker
 */
class BuddyLoader extends Generic implements FileChecker
{
    protected $lineMatch = '#^(?P<fileName>.*?):(?P<lineNumber>[0-9]+) \| (?P<message>.*)$#';

    public static function getDescription()
    {
        return 'Parses buddy (magic number detection) output';
    }
}
