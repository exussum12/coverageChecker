<?php
namespace exussum12\CoverageChecker;

use XMLReader;

class XMLReport implements FileChecker
{
    protected $file;
    protected $coveredLines;
    public function __construct($file)
    {
        $this->file = $file;
    }
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

    public function isValidLine($file, $line)
    {
        return
            isset($this->coveredLines[$file][$line]) &&
            $this->coveredLines[$file][$line] > 0;
    }
}
