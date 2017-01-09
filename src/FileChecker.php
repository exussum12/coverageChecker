<?php
namespace exussum12\CoverageChecker;

/**
 * Interface FileChecker
 * @package exussum12\CoverageChecker
 */
interface FileChecker
{
    /**
     * @return array array containing filename and line numbers
     */
    public function getLines();

    /**
     * Method to determine if the line is valid in the context
     * @param $file
     * @param $lineNumber
     * @return bool
     */
    public function isValidLine($file, $lineNumber);
}
