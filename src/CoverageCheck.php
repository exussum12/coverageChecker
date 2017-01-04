<?php
namespace exussum12\CoverageChecker;

use stdClass;

class CoverageCheck
{
    protected $diff;
    protected $fileChecker;
    protected $matcher;
    protected $cache;
    protected $uncoveredLines = [];
    protected $coveredLines = [];

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
                continue;
            }

            $this->matchLines($file, $matchedFile);
        }

        return [
            'uncoveredLines' => $this->uncoveredLines,
            'coveredLines' => $this->coveredLines,
        ];
    }

    protected function addUnCoveredLine($file, $line)
    {
        if (!isset($this->uncoveredLines[$file])) {
            $this->uncoveredLines[$file] = [];
        }

        $this->uncoveredLines[$file][] = $line;
    }

    protected function addCoveredLine($file, $line)
    {
        if (!isset($this->coveredLines[$file])) {
            $this->coveredLines[$file] = [];
        }

        $this->coveredLines[$file][] = $line;
    }

    protected function matchLines($fileName, $matchedFile)
    {
        foreach ($this->cache->diff[$fileName] as $line) {
            if ($this->fileChecker->isValidLine($matchedFile, $line)) {
                $this->addCoveredLine($fileName, $line);
                continue;
            }
            $this->addUnCoveredLine($fileName, $line);
        }
    }
}
