<?php
namespace exussum12\CoverageChecker;

class PhpMndLoader implements FileChecker
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
    public function parseLines()
    {
        while (($line = fgets($this->file)) !== false) {
            $matches = [];
            if (preg_match("/^(?<filename>[^:]+):(?<lineNo>[0-9]+)\.? (?<message>.+)/", $line, $matches)) {
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
    public function getErrorsOnLine($file, $lineNumber)
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
    public static function getDescription()
    {
        return 'Parses the text output of phpmnd (Magic Number Detection)';
    }
}
