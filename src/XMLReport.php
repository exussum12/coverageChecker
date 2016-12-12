<?php
namespace exussum12\CoverageChecker;

use XMLReader;

class XMLReport
{
    protected $file;
    public function __construct($file)
    {
        $this->file = $file;
    }
    public function getCoveredLines()
    {
        $coveredLines = [];
        $reader = new XMLReader;
        $reader->open($this->file);
        while ($reader->read()) {
            if ((
                $reader->name === "file" &&
                $reader->nodeType == XMLReader::ELEMENT
            )) {
                $currentFile = $reader->getAttribute('name');
                $coveredLines[$currentFile] = [];
            }

            if ((
                $reader->name === "line" &&
                $reader->getAttribute("type") == "stmt"
            )) {
                $coveredLines
                    [$currentFile]
                    [$reader->getAttribute('num')]
                    = (int) $reader->getAttribute("count");
            }
        }

        return $coveredLines;
    }
}
