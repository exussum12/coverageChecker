<?php
namespace exussum12\CoverageChecker;

/**
 * Class PylintLoader
 * Used for parsing reports in Pylint format
 * @package exussum12\CoverageChecker
 */
class PylintLoader extends PhanTextLoader
{
    protected $lineMatch = '#\./(?P<fileName>.*?):(?P<lineNumber>[0-9]+): \[.*?\](?P<message>.*)#';

    public static function getDescription()
    {
        return 'Parses PyLint output';
    }
}
