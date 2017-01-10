<?php
namespace exussum12\CoverageChecker;

use XMLReader;

/**
 * Class PhpMdLoader
 * Used for parsing phpmd xml output
 * @package exussum12\CoverageChecker
 */
class PhpMdLoader implements FileChecker
{
    /**
     * @var string
     */
    protected $file;

    /**
     * PhpMdLoader constructor.
     * @param string $file the path to the phpmd xml file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * {@inheritdoc}
     */
    public function getLines()
    {
        $this->errors = [];
        $reader = new XMLReader;
        $reader->open($this->file);
        $currentFile = "";
        while ($reader->read()) {
            $currentFile = $this->checkForNewFile($reader, $currentFile);
            $this->checkForViolation($reader, $currentFile);
        }

        return $this->errors;
    }

    /**
     * {@inheritdoc}
     */
    public function isValidLine($file, $lineNumber)
    {
        return empty($this->errors[$file][$lineNumber]);
    }

    /**
     * @param XMLReader $reader
     * @param string $currentFile
     */
    protected function checkForViolation(XMLReader $reader, $currentFile)
    {
        if ((
            $reader->name === 'violation' &&
            $reader->nodeType == XMLReader::ELEMENT
        )) {
            $error = trim($reader->readString());
            $start = $reader->getAttribute('beginline');
            $end = $reader->getAttribute('endline');
            for ($i = $start; $i <= $end; $i++) {
                $this->errors[$currentFile][$i][] = $error;
            }
        }
    }

    /**
     * @param XMLReader $reader
     * @param string $currentFile
     * @return string the currentFileName
     */
    protected function checkForNewFile(XMLReader $reader, $currentFile)
    {
        if ((
            $reader->name === 'file' &&
            $reader->nodeType == XMLReader::ELEMENT
        )
        ) {
            $currentFile = $reader->getAttribute('name');
            $this->errors[$currentFile] = [];
            return $currentFile;
        }
        return $currentFile;
    }
}
