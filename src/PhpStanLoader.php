<?php
namespace exussum12\CoverageChecker;

/**
 * Class PhpStanLoader
 * Used for parsing phpstan standard output
 * @package exussum12\CoverageChecker
 */
class PhpStanLoader implements FileChecker
{
    protected $lineRegex = '/^\s+(?<lineNumber>[0-9]+)/';

    protected $file;

    /**
     * @var array
     */
    protected $invalidLines = [];

    /**
     * @param string $filename the path to the phpstan.txt file
     */
    public function __construct($filename)
    {
        $this->file = fopen($filename, 'r');
    }

    /**
     * {@inheritdoc}
     */
    public function getLines()
    {
        $filename = '';
        while (($line = fgets($this->file)) !== false) {
            $filename = $this->checkForFileName($line, $filename);
            if ($lineNumber = $this->getLineNumber($line)) {
                $this->invalidLines
                [$filename]
                [$lineNumber] = $this->getMessage($line);
            }
        }

        return $this->invalidLines;
    }

    /**
     * {@inheritdoc}
     */
    public function isValidLine($file, $lineNumber)
    {
        return empty($this->invalidLines[$file][$lineNumber]);
    }


    /**
     * {@inheritdoc}
     */
    public function handleNotFoundFile()
    {
        return true;
    }

    /**
     * @param string $line
     * @param string $currentFile
     * @return string the currentFileName
     */
    protected function checkForFilename($line, $currentFile)
    {
        if (strpos($line, " Line ")) {
            return trim(str_replace('Line', '',$line));
        }
        return $currentFile;
    }

    protected function getLineNumber($line)
    {
       $matches = [];
       if (!preg_match('' . $this->lineRegex, $line, $matches)) {
           return false;
       }

       return $matches['lineNumber'];
    }

    protected function getMessage($line)
    {
        return trim(preg_replace($this->lineRegex, "", $line));
    }
}
