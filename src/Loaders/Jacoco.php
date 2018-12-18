<?php
namespace exussum12\CoverageChecker\Loaders;

use XMLReader;

/**
 * Class Jacoco
 * Used for reading in a Jacoco coverage report
 * @package exussum12\CoverageChecker
 */
class Jacoco extends Clover
{
    /**
     * {@inheritdoc}
     */
    public function parseLines(): array
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

        return array_keys($this->coveredLines);
    }

    public static function getDescription(): string
    {
        return 'Parses xml coverage report produced by Jacoco';
    }

    /**
     * @param XMLReader $reader
     * @param string $currentFile
     */
    protected function addLine(XMLReader $reader, string $currentFile)
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

    protected function findFile(XMLReader $reader, string $currentNamespace, string $currentFile): string
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

    protected function findNamespace(XMLReader $reader, string $currentNamespace): string
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
