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
    public function getLines()
    {
        $this->coveredLines = [];
        $reader = new XMLReader;
        $reader->open($this->file);
        $currentFile = '';
        while ($reader->read()) {
            if ((
                $reader->name === "file" &&
                $reader->nodeType == XMLReader::ELEMENT
            )) {
                $currentFile = $reader->getAttribute('name');
                $trim = './';
                $currentFile = substr($currentFile, strlen($trim));
                $this->coveredLines[$currentFile] = [];
            }

            if ($reader->name === "error") {
                $this->coveredLines
                    [$currentFile]
                    [$reader->getAttribute('line')]
                    = $reader->getAttribute("message");
            }
        }

        return $this->coveredLines;
    }

    /**
     * {@inheritdoc}
     */
    public function isValidLine($file, $line)
    {
        return empty($this->coveredLines[$file][$line]);
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
}
