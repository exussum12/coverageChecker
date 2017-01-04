<?php
namespace exussum12\CoverageChecker;

use stdClass;

class CoverageCheck
{
    protected $diff;
    protected $xmlReport;
    protected $matcher;
    protected $cache;
    protected $uncoveredLines = [];
    protected $coveredLines = [];

    public function __construct(
        DiffFileLoader $diff,
        XMLReport $xmlReport,
        FileMatcher $matcher
    ) {
        $this->diff = $diff;
        $this->xmlReport = $xmlReport;
        $this->matcher = $matcher;
        $this->cache = new stdClass;
    }

    public function getCoveredLines()
    {
        if (empty($this->cache->diff)) {
            $this->cache->diff = $this->diff->getChangedLines();
        }

        if (empty($this->cache->coveredLines)) {
            $this->cache->coveredLines = $this->xmlReport->getCoveredLines();
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

            $this->matchLines(
                $file,
                $this->cache->coveredLines[$matchedFile]
            );
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

    protected function matchLines($fileName, $unitTestFile)
    {
        foreach ($this->cache->diff[$fileName] as $line) {
            if (isset($unitTestFile[$line]) && $unitTestFile[$line] == 0) {
                $this->addUnCoveredLine($fileName, $line);
            }
            if (isset($unitTestFile[$line]) && $unitTestFile[$line] > 0) {
                $this->addCoveredLine($fileName, $line);
            }
        }
    }
}
