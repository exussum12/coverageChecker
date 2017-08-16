<?php
namespace exussum12\CoverageChecker;

use InvalidArgumentException;

/**
 * Class PhpCsLoaderStrict
 * Used to fail warnings too
 * @package exussum12\CoverageChecker
 */
class PhpCsLoaderStrict extends PhpCsLoader
{
    protected $failOnTypes = [
        'ERROR',
        'WARNING',
    ];

    /**
     * {@inheritdoc}
     */
    public static function getDescription()
    {
        return 'Parses the json report format of phpcs, this mode ' .
            'only reporst errors and warnings as violations';
    }
}
