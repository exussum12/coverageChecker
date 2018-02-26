<?php
namespace exussum12\CoverageChecker;

/**
 * Class PhanTextLoader
 * Used for parsing phan text output
 * @package exussum12\CoverageChecker
 */
class PhanTextLoader extends Generic
{
    protected $lineMatch = '#(?:\./)?(?P<fileName>.*?):(?P<lineNumber>[0-9]+)(?P<message>.*)#';

    /*
     * @inheritdoc
     */
    public static function getDescription()
    {
        return 'Parse the default phan(static analysis) output';
    }
}
