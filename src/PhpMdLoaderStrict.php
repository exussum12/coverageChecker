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
    /**
     * {@inheritdoc}
     * Strict reports every offending line not just the first
     */
    public function isValidLine($file, $lineNumber)
    {
        return empty($this->errors[$file][$lineNumber]);
    }
}
