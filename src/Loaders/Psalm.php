<?php
namespace exussum12\CoverageChecker\Loaders;

use exussum12\CoverageChecker\FileChecker;
use XMLReader;

/**
 * Class Psalm
 * Used for parsing psalm output
 *
 * @package exussum12\CoverageChecker
 */
class Psalm implements FileChecker
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
     * Psalm constructor.
     *
     * @param string $file the path to the psalm xml file
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

        while ($reader->read()) {
            if ($this->isElementBeginning($reader, 'item')) {
                $this->parseItem($reader);
            }
        }

        return array_keys($this->errors);
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorsOnLine(string $file, int $lineNumber)
    {
        $errors = [];
        foreach ($this->errorRanges[$file] as $number => $error) {
            if ((
                $error['start'] <= $lineNumber
                && $error['end'] >= $lineNumber
            )) {
                $errors[] = $error['error'];
                unset($this->errorRanges[$file][$number]);
            }
        }

        return $errors;
    }

    /**
     * @param XMLReader $reader
     */
    protected function parseItem(XMLReader $reader)
    {
        $attributes = [];

        while ($reader->read()) {
            if ($this->isElementEnd($reader, 'item')) {
                break;
            }

            if ($reader->nodeType == XMLReader::ELEMENT) {
                $attributes[$reader->name] = $reader->readString();
            }
        }

        $error = $attributes['message'];
        $start = $attributes['line_from'];
        $end = $attributes['line_to'];
        $fileName = $attributes['file_name'];

        $this->errorRanges[$fileName][] = [
            'start' => $start,
            'end' => $end,
            'error' => $error,
        ];

        $this->addForAllLines($fileName, $start, $end, $error);
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
        return 'Parses the xml report format of psalm';
    }

    protected function addForAllLines($currentFile, $start, $end, $error)
    {
        for ($i = $start; $i <= $end; $i++) {
            if ((
                !isset($this->errors[$currentFile][$i])
                || !in_array($error, $this->errors[$currentFile][$i])
            )
            ) {
                $this->errors[$currentFile][$i][] = $error;
            }
        }
    }

    protected function isElementBeginning(XMLReader $reader, string $name): bool
    {
        return $reader->name === $name && $reader->nodeType == XMLReader::ELEMENT;
    }

    protected function isElementEnd(XMLReader $reader, string $name): bool
    {
        return $reader->name === $name && $reader->nodeType == XMLReader::END_ELEMENT;
    }
}
