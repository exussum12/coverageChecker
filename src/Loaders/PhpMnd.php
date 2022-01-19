<?php
namespace exussum12\CoverageChecker\Loaders;

use exussum12\CoverageChecker\FileChecker;

class PhpMnd implements FileChecker
{
    private $invalidLines = [];

    private $file;

    public function __construct($filename)
    {
        $this->file = fopen($filename, 'r');
    }

    /**
     * @inheritdoc
     */
    public function parseLines(): array
    {
        while (($line = fgets($this->file)) !== false) {
            $matches = [];
            $pattern = "/^(?<filename>[^:]+):(?<lineNo>[0-9]+)\.? (?<message>.+)/";
            if (preg_match($pattern, $line, $matches)) {
                $this->invalidLines
                    [$matches['filename']]
                    [$matches['lineNo']][] = $matches['message'];
            }
        }

        return array_keys($this->invalidLines);
    }

    /**
     * @inheritdoc
     */
    public function getErrorsOnLine(string $file, int $lineNumber)
    {
        $errors = [];
        if (isset($this->invalidLines[$file][$lineNumber])) {
            $errors = $this->invalidLines[$file][$lineNumber];
        }

        return $errors;
    }

    /**
     * return as true to include files, phpmnd only shows files with errors
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
        return 'Parses the text output of phpmnd (Magic Number Detection)';
    }
}
