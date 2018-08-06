<?php
namespace exussum12\CoverageChecker\Loaders;

use XMLReader;
use exussum12\CoverageChecker\FileChecker;

class PhpMndXml implements FileChecker
{
    protected $currentLine;
    private $invalidLines = [];

    private $file;

    public function __construct($filename)
    {
        $this->file = $filename;
    }

    /**
     * @inheritdoc
     */
    public function parseLines(): array
    {
        $reader = new XMLReader;
        $reader->open($this->file);
        $currentFile = '';
        while ($reader->read()) {
            $currentFile = $this->checkForNewFiles($reader, $currentFile);

            $this->handleLine($reader, $currentFile);
            $this->handleErrors($reader, $currentFile);
        }

        return array_keys($this->invalidLines);
    }

    protected function checkForNewFiles(XMLReader $reader, $currentFile)
    {
        if ((
            $reader->name === "file" &&
            $reader->nodeType == XMLReader::ELEMENT
        )) {
            $currentFile = $reader->getAttribute('path');
            $this->invalidLines[$currentFile] = [];
        }
        return $currentFile;
    }

    protected function handleLine(XMLReader $reader, $currentFile)
    {
        if ($reader->name === "entry") {
            $this->currentLine = $reader->getAttribute("line");
            if (!isset($this->invalidLines[$currentFile][$this->currentLine])) {
                $this->invalidLines[$currentFile][$this->currentLine] = [];
            }
        }
    }

    protected function handleErrors(XMLReader $reader, $currentFile)
    {
        if ((
            $reader->name === "snippet" &&
            $reader->nodeType == XMLReader::ELEMENT
        )) {
            $this->invalidLines[$currentFile][$this->currentLine][] = $reader->readString();
        }
    }

    /**
     * @inheritdoc
     */
    public function getErrorsOnLine(string $file, int $lineNumber)
    {
        $errors = [];
        if (isset($this->invalidLines[$file][$lineNumber])) {
            $errors = $this->invalidLines[$file][$lineNumber];
        }

        return $errors;
    }

    /**
     * return as true to include files, phpmnd only shows files with errors
     */
    public function handleNotFoundFile()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function getDescription(): string
    {
        return 'Parses the XML output of phpmnd (Magic Number Detection)';
    }
}
