<?php
namespace exussum12\CoverageChecker;

use XMLReader;

/**
 * Class XMLReport
 * Used for reading in a phpunit clover XML file
 * @package exussum12\CoverageChecker
 */
class PhpUnitLoader extends CloverLoader
{
    /**
     * {@inheritdoc}
     */
    public static function getDescription()
    {
        return 'Parses text output in clover (xml) format ' .
            'generated with coverage-clover=file.xml';
    }
}
