<?php
namespace exussum12\CoverageChecker;

/**
 * Class PhanJsonLoader
 * Used for parsing phan json output
 * @package exussum12\CoverageChecker
 */
class PhanJsonLoader extends CodeClimateLoader
{
    public function __construct($file)
    {
        $this->file = json_decode(file_get_contents($file));
    }

    public static function getDescription()
    {
        return 'Parses phan (static analysis) in json format';
    }
}
