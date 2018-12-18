<?php
namespace exussum12\CoverageChecker\Loaders;

/**
 * Class PhpUnit
 * Used for reading in a phpunit clover XML file
 * @package exussum12\CoverageChecker
 */
class PhpUnit extends Clover
{
    /**
     * {@inheritdoc}
     */
    public static function getDescription(): string
    {
        return 'Parses text output in clover (xml) format ' .
            'generated with --coverage-clover=file.xml';
    }
}
