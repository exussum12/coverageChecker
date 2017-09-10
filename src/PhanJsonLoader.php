<?php
namespace exussum12\CoverageChecker;

/**
 * Class PhanJsonLoader
 * Used for parsing phan json output
 * @package exussum12\CoverageChecker
 */
class PhanJsonLoader extends CodeClimateLoader
{
    public static function getDescription()
    {
        return 'Parses phan (static analysis) in json format';
    }
}
