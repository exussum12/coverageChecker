<?php
namespace exussum12\CoverageChecker;

use XMLReader;

class PhpMndLoader implements FileChecker
{
    private $invalidLines = [];

    private $file;

    public function __construct($filename)
    {
        $this->file = fopen($filename, 'r');
    }
    /**
     * @return array array containing filename and line numbers
     */
    public function getLines()
    {
        while (($line = fgets($this->file)) !== false) {
            $matches = [];
            if (preg_match("/^(?<filename>[^:]+):(?<lineNo>[0-9]+)\. (?<message>.+)/", $line, $matches)) {
                $this->invalidLines
                    [$matches['filename']]
                    [$matches['lineNo']] = $matches['message'];
            }
        }

        return $this->invalidLines;
    }

    /**
     * Method to determine if the line is valid in the context
     * null does not include the line in the stats
     * @param $file
     * @param $lineNumber
     * @return bool|null
     */
    public function isValidLine($file, $lineNumber)
    {
        return empty($this->invalidLines[$file][$lineNumber]);
    }

    public function handleNotFoundFile() {
        return true;
    }
}
