<?php
namespace exussum12\CoverageChecker;

use XMLReader;

/**
 * Class CheckstyleLoader
 * Used for reading in a report in checkstyle format
 * @package exussum12\CoverageChecker
 */
class CheckstyleLoader implements FileChecker
{
    /**
     * @var string
     */
    protected $file;
    /**
     * @var array
     */
    protected $coveredLines;

    /**
     * XMLReport constructor.
     * @param string $file the path the to phpunit clover file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * {@inheritdoc}
     */
    public function parseLines()
    {
        $this->coveredLines = [];
        $reader = new XMLReader;
        $reader->open($this->file);
        $currentFile = '';
        while ($reader->read()) {
            $currentFile = $this->handleFile($reader, $currentFile);

            $this->handleErrors($reader, $currentFile);
        }

        return array_keys($this->coveredLines);
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorsOnLine($file, $line)
    {
        $errors = [];
        if (isset($this->coveredLines[$file][$line])) {
           $errors = $this->coveredLines[$file][$line];
        }

        return $errors;
    }

    /**
     * {@inheritdoc}
     */
    public function handleNotFoundFile()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function getDescription()
    {
        return 'Parses a report in checkstyle format';
    }

    /**
     * @param $reader
     * @param $currentFile
     */
    protected function handleErrors($reader, $currentFile)
    {
        if ($reader->name === "error") {
            $this->coveredLines
            [$currentFile]
            [$reader->getAttribute('line')][]
                = $reader->getAttribute("message");
        }
    }

    /**
     * @param $reader
     * @param $currentFile
     * @return string
     */
    protected function handleFile($reader, $currentFile)
    {
        if ((
            $reader->name === "file" &&
            $reader->nodeType == XMLReader::ELEMENT
        )) {
            $currentFile = $reader->getAttribute('name');
            $trim = './';
            $currentFile = substr($currentFile, strlen($trim));
            $this->coveredLines[$currentFile] = [];
        }
        return $currentFile;
    }
}
