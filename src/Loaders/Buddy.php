<?php
namespace exussum12\CoverageChecker\Loaders;

use exussum12\CoverageChecker\FileChecker;

/**
 * Class Buddy
 * Used for parsing magic number reports from buddy
 * @package exussum12\CoverageChecker
 */
class Buddy extends Generic implements FileChecker
{
    protected $lineMatch = '#^(?P<fileName>.*?):(?P<lineNumber>[0-9]+) \| (?P<message>.*)$#';

    public static function getDescription(): string
    {
        return 'Parses buddy (magic number detection) output';
    }
}
