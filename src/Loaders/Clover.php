<?php
namespace exussum12\CoverageChecker\Loaders;

use exussum12\CoverageChecker\FileChecker;
use XMLReader;

/**
 * Class XMLReport
 * Used for reading in a phpunit clover XML file
 * @package exussum12\CoverageChecker
 */
class Clover implements FileChecker
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
    public function parseLines(): array
    {
        $this->coveredLines = [];
        $reader = new XMLReader;
        $reader->open($this->file);
        $currentFile = '';
        while ($reader->read()) {
            $currentFile = $this->checkForNewFiles($reader, $currentFile);

            $this->handleStatement($reader, $currentFile);
        }

        return array_keys($this->coveredLines);
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorsOnLine(string $file, int $lineNumber)
    {
        if (!isset($this->coveredLines[$file][$lineNumber])) {
            return null;
        }
        return $this->coveredLines[$file][$lineNumber] > 0 ?
            []:
            ['No unit test covering this line']
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function handleNotFoundFile()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public static function getDescription(): string
    {
        return 'Parses text output in clover (xml) format';
    }

    protected function checkForNewFiles(XMLReader $reader, string $currentFile)
    {
        if ((
            $reader->name === "file" &&
            $reader->nodeType == XMLReader::ELEMENT
        )) {
            $currentFile = $reader->getAttribute('name');
            $this->coveredLines[$currentFile] = [];
        }
        return $currentFile;
    }

    protected function addLine(XMLReader $reader, string $currentFile)
    {
        $covered = $reader->getAttribute('count') > 0;
        $line = $this->coveredLines
        [$currentFile]
        [$reader->getAttribute('num')] ?? 0;

        $this->coveredLines
        [$currentFile]
        [$reader->getAttribute('num')] = $line + $covered;
    }

    protected function handleStatement(XMLReader $reader, string $currentFile)
    {
        if ((
            $reader->name === "line" &&
            $reader->getAttribute("type") == "stmt"
        )) {
            $this->addLine($reader, $currentFile);
        }
    }
}
