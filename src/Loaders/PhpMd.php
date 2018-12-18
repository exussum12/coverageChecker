<?php
namespace exussum12\CoverageChecker\Loaders;

use exussum12\CoverageChecker\FileChecker;
use XMLReader;

/**
 * Class PhpMd
 * Used for parsing phpmd xml output
 * @package exussum12\CoverageChecker
 */
class PhpMd implements FileChecker
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
    public function parseLines(): array
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

        return array_keys($this->errors);
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorsOnLine(string $file, int $lineNumber)
    {
        $errors = [];
        if (empty($this->errorRanges[$file])) {
            return $errors;
        }

        foreach ($this->errorRanges[$file] as $number => $error) {
            if ((
                $error['start'] <= $lineNumber &&
                $error['end'] >= $lineNumber
            )) {
                $errors[] = $error['error'];
                unset($this->errorRanges[$file][$number]);
            }
        }

        return $errors;
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

            $this->addForAllLines($currentFile, $start, $end, $error);
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

    /**
     * {@inheritdoc}
     */
    public static function getDescription(): string
    {
        return 'Parses the xml report format of phpmd, this mode ' .
            'reports multi line violations once per diff, instead ' .
            'of on each line the violation occurs';
    }

    protected function addForAllLines($currentFile, $start, $end, $error)
    {
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
