<?php
namespace exussum12\CoverageChecker;

use ReflectionFunction;
use ReflectionMethod;
use Reflector;

/**
 * Class PhpStanLoader
 * Used for parsing phpstan standard output
 * @package exussum12\CoverageChecker
 */
class PhpStanLoader implements FileChecker
{
    protected $lineRegex = '/^\s+(?<lineNumber>[0-9]+)/';

    protected $file;
    protected $relatedRegex = '#(function|method) (?:(?P<class>.*?)::)?(?P<function>.*?)[ \(]#';

    /**
     * @var array
     */
    protected $invalidLines = [];

    /**
     * @param string $filename the path to the phpstan.txt file
     */
    public function __construct($filename)
    {
        $this->file = fopen($filename, 'r');
    }

    /**
     * {@inheritdoc}
     */
    public function getLines()
    {
        $filename = '';
        $lineNumber = 0;
        while (($line = fgets($this->file)) !== false) {
            $filename = $this->checkForFileName($line, $filename);
            if ($lineNumber = $this->getLineNumber($line, $lineNumber)) {
                $error = $this->getMessage($line);
                $this->handleRelatedError($filename, $lineNumber, $error);

                $this->addError($filename, $lineNumber, $error);
            }
        }

        $this->trimLines();

        return $this->invalidLines;
    }

    /**
     * {@inheritdoc}
     */
    public function isValidLine($file, $lineNumber)
    {
        return empty($this->invalidLines[$file][$lineNumber]);
    }


    /**
     * {@inheritdoc}
     */
    public function handleNotFoundFile()
    {
        return true;
    }

    /**
     * @param string $line
     * @param string $currentFile
     * @return string the currentFileName
     */
    protected function checkForFilename($line, $currentFile)
    {
        if (strpos($line, " Line ")) {
            return trim(str_replace('Line', '', $line));
        }
        return $currentFile;
    }

    protected function getLineNumber($line, $currentLineNumber)
    {
        $matches = [];
        if (!preg_match($this->lineRegex, $line, $matches)) {
            if (preg_match('#^\s{3,}#', $line)) {
                return $currentLineNumber;
            }

            return false;
        }

        return $matches['lineNumber'];
    }

    protected function getMessage($line)
    {
        return trim(preg_replace($this->lineRegex, '', $line));
    }

    protected function trimLines()
    {
        array_walk_recursive($this->invalidLines, function (&$item) {
            if (is_string($item)) {
                $item = trim($item);
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public static function getDescription()
    {
        return 'Parses the text output of phpstan';
    }

    protected function handleRelatedError($filename, $line, $error)
    {

        $matches = [];
        if (preg_match($this->relatedRegex, $error, $matches)) {
            $error = sprintf(
                '%s (used %s line %d)',
                $error,
                $filename,
                $line
            );

            $reflection = $this->getReflector($matches);
            if ($reflection && ($filename = $reflection->getFileName())) {
                $currentLine = $reflection->getStartLine();
                while ($currentLine < $reflection->getEndLine()) {
                    $this->addError($filename, $currentLine++, $error);
                }
            }
        }
    }

    /**
     * @param $filename
     * @param $lineNumber
     * @param $error
     */
    protected function addError($filename, $lineNumber, $error)
    {
        if (!isset($this->invalidLines[$filename][$lineNumber])) {
            $this->invalidLines[$filename][$lineNumber] = '';
        }
        $this->invalidLines[$filename][$lineNumber] .= $error . ' ';
    }

    /**
     * @param $matches
     * @return Reflector
     */
    protected function getReflector($matches)
    {
        if ($matches['class']) {
            return new ReflectionMethod(
                $matches['class'],
                $matches['function']
            );
        }

        return new ReflectionFunction(
            $matches['function']
        );
    }
}
