<?php
namespace exussum12\CoverageChecker;

use XMLReader;

/**
 * Class XMLReport
 * Used for reading in a phpunit clover XML file
 * @package exussum12\CoverageChecker
 */
class XMLReport implements FileChecker
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
        while ($reader->read()) {
            if ((
                $reader->name === "file" &&
                $reader->nodeType == XMLReader::ELEMENT
            )) {
                $currentFile = $reader->getAttribute('name');
                $this->coveredLines[$currentFile] = [];
            }

            if ((
                $reader->name === "line" &&
                $reader->getAttribute("type") == "stmt"
            )) {
                $this->coveredLines
                    [$currentFile]
                    [$reader->getAttribute('num')]
                    = (int) $reader->getAttribute("count");
            }
        }

        return $this->coveredLines;
    }

    /**
     * {@inheritdoc}
     */
    public function isValidLine($file, $line)
    {
        return
            !isset($this->coveredLines[$file][$line]) ||
            $this->coveredLines[$file][$line] > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function handleNotFoundFile()
    {
        return null;
    }
}
