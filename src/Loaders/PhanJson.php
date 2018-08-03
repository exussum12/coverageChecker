<?php
namespace exussum12\CoverageChecker\Loaders;

/**
 * Class PhanJson
 * Used for parsing phan json output
 * @package exussum12\CoverageChecker
 */
class PhanJson extends CodeClimate
{
    public function __construct($file)
    {
        $this->file = json_decode(file_get_contents($file));
    }

    public static function getDescription(): string
    {
        return 'Parses phan (static analysis) in json format';
    }
}
