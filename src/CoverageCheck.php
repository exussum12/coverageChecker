<?php
namespace exussum12\CoverageChecker;

use stdClass;

/**
 * Class CoverageCheck
 * @package exussum12\CoverageChecker
 */
class CoverageCheck
{
    /**
     * @var DiffFileLoader
     */
    protected $diff;
    /**
     * @var FileChecker
     */
    protected $fileChecker;
    /**
     * @var FileMatcher
     */
    protected $matcher;
    /**
     * @var stdClass
     */
    protected $cache;
    /**
     * @var array
     */
    protected $uncoveredLines = [];
    /**
     * @var array
     */
    protected $coveredLines = [];

    /**
     * CoverageCheck constructor.
     * This class is used for filtering the "checker" by the diff
     * For example if the checker is phpunit, this class filters the phpunit
     * output by the diff of the pull request. giving only the common lines in
     * each
     *
     * @param DiffFileLoader $diff
     * @param FileChecker $fileChecker
     * @param FileMatcher $matcher
     */
    public function __construct(
        DiffFileLoader $diff,
        FileChecker $fileChecker,
        FileMatcher $matcher
    ) {
        $this->diff = $diff;
        $this->fileChecker = $fileChecker;
        $this->matcher = $matcher;
        $this->cache = new stdClass;
    }

    /**
     * array of uncoveredLines and coveredLines
     * @return array
     */
    public function getCoveredLines()
    {
        if (empty($this->cache->diff)) {
            $this->cache->diff = $this->diff->getChangedLines();
        }

        if (empty($this->cache->coveredLines)) {
            $this->cache->coveredLines = $this->fileChecker->getLines();
        }

        $this->uncoveredLines = [];
        $this->coveredLines = [];

        $diffFiles = array_keys($this->cache->diff);
        $coveredFiles = array_keys($this->cache->coveredLines);
        foreach ($diffFiles as $file) {
            try {
                $matchedFile = $this->matcher->match($file, $coveredFiles);
            } catch (Exceptions\FileNotFound $e) {
                $unMatchedFile = $this->fileChecker->handleNotFoundFile();

                if ($unMatchedFile === true) {
                    $this->addCoveredFile($file);
                }

                if ($unMatchedFile === false) {
                    $this->addUnCoveredFile($file);
                }

                continue;
            }

            $this->matchLines($file, $matchedFile);
        }

        return [
            'uncoveredLines' => $this->uncoveredLines,
            'coveredLines' => $this->coveredLines,
        ];
    }

    /**
     * @param string $file the filename containing the uncovered line
     * @param int $line the number of the uncovered line
     * @param string $message the message showing why its uncovered
     */
    protected function addUnCoveredLine($file, $line, $message)
    {
        if (!isset($this->uncoveredLines[$file])) {
            $this->uncoveredLines[$file] = [];
        }

        $this->uncoveredLines[$file][$line] = $message;
    }

    /**
     * @param string $file the filename containing the covered line
     * @param int $line the number of the covered line
     */
    protected function addCoveredLine($file, $line)
    {
        if (!isset($this->coveredLines[$file])) {
            $this->coveredLines[$file] = [];
        }

        $this->coveredLines[$file][] = $line;
    }

    /**
     * @param string $fileName the file name in the diff
     * @param string $matchedFile the file name of the matched file
     */
    protected function matchLines($fileName, $matchedFile)
    {
        foreach ($this->cache->diff[$fileName] as $line) {
            if ($this->fileChecker->isValidLine($matchedFile, $line)) {
                $this->addCoveredLine($fileName, $line);
                continue;
            }

            $message = isset($this->cache->coveredLines[$matchedFile][$line])
                ? $this->cache->coveredLines[$matchedFile][$line] :
                "No cover"
            ;

            $this->addUnCoveredLine(
                $fileName,
                $line,
                $message
            );
        }
    }

    protected function addCoveredFile($file)
    {
        foreach ($this->cache->diff[$file] as $line) {
            $this->addCoveredLine($file, $line);
        }
    }

    protected function addUnCoveredFile($file)
    {
        foreach ($this->cache->diff[$file] as $line) {
            $this->addUnCoveredLine($file, $line, 0);
        }
    }
}
