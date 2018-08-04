<?php
namespace exussum12\CoverageChecker\Loaders;

use exussum12\CoverageChecker\FileChecker;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;

/**
 * Class PhpStan
 * Used for parsing phpstan standard output
 * @package exussum12\CoverageChecker
 */
class PhpStan implements FileChecker
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
    public function parseLines(): array
    {
        $filename = '';
        $lineNumber = 0;
        while (($line = fgets($this->file)) !== false) {
            $filename = $this->checkForFilename($line, $filename);
            if ($lineNumber = $this->getLineNumber($line, $lineNumber)) {
                $error = $this->getMessage($line);
                if ($this->isExtendedMessage($line)) {
                    $this->appendError($filename, $lineNumber, $error);
                    continue;
                }
                $this->handleRelatedError($filename, $lineNumber, $error);
                $this->addError($filename, $lineNumber, $error);
            }
        }

        $this->trimLines();

        return array_keys($this->invalidLines);
    }

    /**
     * {@inheritdoc}
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

    protected function getLineNumber(string $line, int $currentLineNumber)
    {
        $matches = [];
        if (!preg_match($this->lineRegex, $line, $matches)) {
            if (preg_match('#^\s{3,}#', $line)) {
                return $currentLineNumber;
            }

            return false;
        }

        return (int) $matches['lineNumber'];
    }

    protected function getMessage($line)
    {
        return trim(preg_replace($this->lineRegex, '', $line));
    }

    protected function isExtendedMessage($line)
    {
        return preg_match($this->lineRegex, $line) === 0;
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
    public static function getDescription(): string
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
     * @param string $filename
     * @param int $lineNumber
     * @param string $error
     */
    protected function addError($filename, $lineNumber, $error)
    {
        if (!isset($this->invalidLines[$filename][$lineNumber])) {
            $this->invalidLines[$filename][$lineNumber] = [];
        }
        $this->invalidLines[$filename][$lineNumber][] = $error;
    }

    protected function getReflector(array $matches):  ReflectionFunctionAbstract
    {
        if ($matches['class']) {
            return $this->getClassReflector($matches);
        }

        return $this->getFunctionReflector($matches);
    }

    private function appendError($filename, $lineNumber, $error)
    {
        end($this->invalidLines[$filename][$lineNumber]);
        $key = key($this->invalidLines[$filename][$lineNumber]);
        $this->invalidLines[$filename][$lineNumber][$key] .= ' ' . $error;
    }

    protected function getClassReflector(array $matches)
    {
        if (!method_exists($matches['class'], $matches['function'])) {
            return false;
        }
        return new ReflectionMethod(
            $matches['class'],
            $matches['function']
        );
    }

    /**
     * @return bool|ReflectionFunction
     */
    protected function getFunctionReflector(array $matches)
    {
        if (!function_exists($matches['function'])) {
            return false;
        }
        return new ReflectionFunction(
            $matches['function']
        );
    }
}
