<?php
namespace exussum12\CoverageChecker;

use XMLReader;

class PhpMdLoader implements FileChecker
{
    protected $file;
    public function __construct($filepath)
    {
        $this->file = $filepath;
    }

    public function getLines()
    {
        $this->errors = [];
        $reader = new XMLReader;
        $reader->open($this->file);
        while ($reader->read()) {
            if ((
                $reader->name === 'file' &&
                $reader->nodeType == XMLReader::ELEMENT
            )) {
                $currentFile = $reader->getAttribute('name');
                $this->errors[$currentFile] = [];
            }

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

        return $this->errors;
    }

    public function isValidLine($file, $lineNumber)
    {
        return empty($this->errors[$file][$lineNumber]);
    }
}
