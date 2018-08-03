<?php
namespace exussum12\CoverageChecker\Loaders;

/**
 * Class PhpCsStrict
 * Used to fail warnings too
 * @package exussum12\CoverageChecker
 */
class PhpCsStrict extends PhpCs
{
    protected $failOnTypes = [
        'ERROR',
        'WARNING',
    ];

    /**
     * {@inheritdoc}
     */
    public static function getDescription(): string
    {
        return 'Parses the json report format of phpcs, this mode ' .
            'reports errors and warnings as violations';
    }
}
