<?php
namespace exussum12\CoverageChecker;

/**
 * Interface FileChecker
 * @package exussum12\CoverageChecker
 */
interface FileChecker
{
    /**
     * @return array the list of files from this change
     */
    public function parseLines();

    /**
     * Method to determine if the line is valid in the context
     * returning null does not include the line in the stats
     * Returns an array containing errors on a certain line - empty array means no errors
     * @param $file
     * @param $lineNumber
     * @return array|null
     */
    public function getErrorsOnLine($file, $lineNumber);

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
