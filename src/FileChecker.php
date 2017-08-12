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
     * null does not include the line in the stats
     * @param $file
     * @param $lineNumber
     * @return bool|null
     */
    public function isValidLine($file, $lineNumber);

    /**
     * Method to determine what happens to files which have not been found
     * true adds as covered
     * false adds as uncovered
     * null does not include the file in the stats
     * @return bool|null
     */
    public function handleNotFoundFile();

    /**
     * Shows the description of the class, used for explaining why
     * this checker would be used
     * @return string
     */
    public static function getDescription();
}
