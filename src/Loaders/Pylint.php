<?php
namespace exussum12\CoverageChecker\Loaders;

use exussum12\CoverageChecker\FileChecker;

/**
 * Class Pylint
 * Used for parsing reports in Pylint format
 * @package exussum12\CoverageChecker
 */
class Pylint extends Generic implements FileChecker
{
    protected $lineMatch = '#\./(?P<fileName>.*?):(?P<lineNumber>[0-9]+): \[.*?\](?P<message>.*)#';

    public static function getDescription(): string
    {
        return 'Parses PyLint output';
    }
}
