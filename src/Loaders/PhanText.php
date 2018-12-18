<?php
namespace exussum12\CoverageChecker\Loaders;

use exussum12\CoverageChecker\FileChecker;

/**
 * Class PhanText
 * Used for parsing phan text output
 * @package exussum12\CoverageChecker
 */
class PhanText extends Generic implements FileChecker
{
    protected $lineMatch = '#(?:\./)?(?P<fileName>.*?):(?P<lineNumber>[0-9]+)(?P<message>.*)#';

    /*
     * @inheritdoc
     */
    public static function getDescription(): string
    {
        return 'Parse the default phan(static analysis) output';
    }
}
