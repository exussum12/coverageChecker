<?php
namespace exussum12\CoverageChecker;

use XMLReader;

/**
 * Class PhpMdLoaderStrict
 * Used for parsing phpmd xml output
 * Strict mode reports errors multiple times
 * @package exussum12\CoverageChecker
 */
class PhpMdLoaderStrict extends PhpMdLoader
{
    public function getErrorsOnLine($file, $lineNumber)
    {
        $errors = [];
        foreach ($this->errorRanges[$file] as $number => $error) {
            if ((
                $error['start'] <= $lineNumber &&
                $error['end'] >= $lineNumber
            )) {
                $errors[] = $error['error'];
            }
        }

        return $errors;
    }

    /**
     * {@inheritdoc}
     */
    public static function getDescription()
    {
        return 'Parses the xml report format of phpmd, this mode ' .
            'reports multi line violations once per line they occur ';
    }
}
