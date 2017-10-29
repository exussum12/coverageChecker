<?php
namespace exussum12\CoverageChecker;

/**
 * Class PhanTextLoader
 * Used for parsing phan text output
 * @package exussum12\CoverageChecker
 */
class PhanTextLoader implements FileChecker
{
    protected $lineMatch = '#(?:\./)?(?P<fileName>.*?):(?P<lineNumber>[0-9]+)(?P<message>.*)#';

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
    public function parseLines()
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
    public function getErrorsOnLine($file, $lineNumber)
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

    /**
     * {@inheritdoc}
     */
    public static function getDescription()
    {
        return 'Parse the default phan(static analysis) output';
    }

    private function checkForFile($line)
    {
        return preg_match($this->lineMatch, $line);
    }

    private function addError($line)
    {
        $matches = [];
        preg_match($this->lineMatch, $line, $matches);
        $this->errors[$matches['fileName']][$matches['lineNumber']][] = trim($matches['message']);
    }
}
