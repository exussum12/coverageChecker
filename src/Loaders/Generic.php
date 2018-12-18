<?php
namespace exussum12\CoverageChecker\Loaders;

use exussum12\CoverageChecker\FileChecker;

/**
 * Class Generic
 * Used for parsing output on a single line
 * @package exussum12\CoverageChecker
 */
abstract class Generic implements FileChecker
{
    protected $lineMatch = '';

    /**
     * @var string
     */
    protected $file;

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * PhanJsonLoader constructor.
     * @param string $file the path to the file containing phan output
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
        $handle = fopen($this->file, 'r');
        while (($line = fgets($handle)) !== false) {
            if (!$this->checkForFile($line)) {
                continue;
            }

            $this->addError($line);
        }

        return array_keys($this->errors);
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorsOnLine(string $file, int $lineNumber)
    {
        $errors = [];
        if (isset($this->errors[$file][$lineNumber])) {
            $errors = $this->errors[$file][$lineNumber];
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


    private function checkForFile(string $line)
    {
        return preg_match($this->lineMatch, $line);
    }

    private function addError(string $line)
    {
        $matches = [];
        if (preg_match($this->lineMatch, $line, $matches)) {
            $this->errors
                [$matches['fileName']]
                [$matches['lineNumber']][] = trim($matches['message']);
        }
    }
}
