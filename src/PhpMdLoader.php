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
     * @var array
     */
    protected $errors = [];

    /**
     * @var array
     */
    protected $errorRanges = [];

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
        $this->errorRanges = [];
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
        $valid = true;
        foreach ($this->errorRanges[$file] as $number => $errors) {
            if ((
                $errors['start'] >= $lineNumber &&
                $errors['end'] <= $lineNumber
            )) {
                //unset this error
                unset($this->errorRanges[$file][$number]);
                $valid = false;
            }
        }

        return $valid;
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
            $this->errorRanges[$currentFile][] = [
                'start' => $start,
                'end' => $end,
                'error' => $error,
            ];

            for ($i = $start; $i <= $end; $i++) {
                if ((
                    !isset($this->errors[$currentFile][$i]) ||
                    !in_array($error, $this->errors[$currentFile][$i])
                )) {
                    $this->errors[$currentFile][$i][] = $error;
                }
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

    /**
     * {@inheritdoc}
     */
    public function handleNotFoundFile()
    {
        return true;
    }
}
