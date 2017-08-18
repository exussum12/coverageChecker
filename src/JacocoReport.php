<?php
namespace exussum12\CoverageChecker;

use XMLReader;

/**
 * Class JacocoReport
 * Used for reading in a Jacoco coverage report
 * @package exussum12\CoverageChecker
 */
class JacocoReport extends XMLReport
{
    /**
     * {@inheritdoc}
     */
    public function getLines()
    {
        $this->coveredLines = [];
        $reader = new XMLReader;
        $reader->open($this->file);
        $currentNamespace = '';
        $currentFile = '';

        while ($reader->read()) {
            $currentNamespace = $this->findNamespace($reader, $currentNamespace);

            $currentFile = $this->findFile($reader, $currentNamespace, $currentFile);

            $this->addLine($reader, $currentFile);
        }

        return $this->coveredLines;
    }

    public static function getDescription()
    {
        return 'Parses xml coverage report produced by Jacoco';
    }

    /**
     * @param $reader
     * @param $currentFile
     */
    protected function addLine($reader, $currentFile)
    {
        if ((
            $reader->name === "line"
        )) {
            $this->coveredLines
            [$currentFile]
            [$reader->getAttribute('nr')]
                = $reader->getAttribute("mi") == 0;
        }
    }

    protected function findFile($reader, $currentNamespace, $currentFile)
    {
        if ((
            $reader->name === "sourcefile" &&
            $reader->nodeType == XMLReader::ELEMENT
        )) {
            $currentFile = $currentNamespace . '/' . $reader->getAttribute('name');
            $this->coveredLines[$currentFile] = [];
        }

        return $currentFile;
    }

    protected function findNamespace($reader, $currentNamespace)
    {
        if ((
            $reader->name === "package" &&
            $reader->nodeType == XMLReader::ELEMENT
        )) {
            $currentNamespace = $reader->getAttribute('name');
        }
        return $currentNamespace;
    }
}
