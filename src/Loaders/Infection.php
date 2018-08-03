<?php
namespace exussum12\CoverageChecker\Loaders;

use exussum12\CoverageChecker\FileChecker;

class Infection implements FileChecker
{

    protected $file;
    protected $errors = [];

    protected $errorTypes = [
        'Escaped mutants',
        'Errors mutants',
        'Not covered mutants',
    ];

    protected $currentFile;

    protected $currentLine;

    protected $partialError;

    protected $currentType;

    public function __construct($filePath)
    {
        $this->file = fopen($filePath, 'r');
    }

    /**
     * @return array the list of files from this change
     */
    public function parseLines(): array
    {
        $this->currentFile = '';
        $this->currentLine = 0;
        $this->partialError = '';
        $this->currentType = '';

        while (($line = fgets($this->file)) !== false) {
            $this->handleLine($line);
        }
        // the last error in the file
        $this->addError();

        return array_keys($this->errors);
    }

    /**
     * Method to determine if the line is valid in the context
     * returning null does not include the line in the stats
     * Returns an array containing errors on a certain line - empty array means no errors
     *
     * @return array|null
     */
    public function getErrorsOnLine(string $file, int $lineNumber)
    {
        if (!isset($this->errors[$file][$lineNumber])) {
            return [];
        }

        return $this->errors[$file][$lineNumber];
    }

    /**
     * Method to determine what happens to files which have not been found
     * true adds as covered
     * false adds as uncovered
     * null does not include the file in the stats
     * @return bool|null
     */
    public function handleNotFoundFile()
    {
        return true;
    }

    /**
     * Shows the description of the class, used for explaining why
     * this checker would be used
     * @return string
     */
    public static function getDescription(): string
    {
        return 'Parses the infection text log format';
    }

    protected function updateType($line)
    {
        $matches = [];
        if (preg_match('/^([a-z ]+):$/i', $line, $matches)) {
            $this->addError();
            $this->currentFile = '';
            $this->currentLine = '';
            $this->partialError = '';
            $this->currentType = $matches[1];

            return true;
        }

        return false;
    }

    protected function updateFile($line)
    {
        $matches = [];
        if (preg_match('/^[0-9]+\) (.*?):([0-9]+) (.*)/i', $line, $matches)) {
            $this->addError();
            $this->currentFile = $matches[1];
            $this->currentLine = $matches[2];
            $this->partialError = '';

            return true;
        }

        return false;
    }

    protected function addError()
    {
        if (!($this->currentFile && $this->currentLine)) {
            return;
        }

        if (!in_array($this->currentType, $this->errorTypes)) {
            return;
        }

        $this->errors
            [$this->currentFile]
            [$this->currentLine][] = $this->currentType . $this->partialError;
    }

    protected function handleLine($line)
    {
        if ($this->updateType($line)) {
            return;
        }

        if ($this->updateFile($line)) {
            return;
        }

        $this->partialError .= $line;
    }
}
