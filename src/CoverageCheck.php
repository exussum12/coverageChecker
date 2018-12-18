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
     */
    public function getCoveredLines(): array
    {
        $this->getDiff();

        $coveredFiles = $this->fileChecker->parseLines();
        $this->uncoveredLines = [];
        $this->coveredLines = [];

        $diffFiles = array_keys($this->cache->diff);
        foreach ($diffFiles as $file) {
            $matchedFile = $this->findFile($file, $coveredFiles);
            if ($matchedFile !== '') {
                $this->matchLines($file, $matchedFile);
            }
        }

        return [
            'uncoveredLines' => $this->uncoveredLines,
            'coveredLines' => $this->coveredLines,
        ];
    }

    protected function addUnCoveredLine(string $file, int $line, array $message)
    {
        if (!isset($this->uncoveredLines[$file])) {
            $this->uncoveredLines[$file] = [];
        }

        $this->uncoveredLines[$file][$line] = $message;
    }

    protected function addCoveredLine(string $file, int $line)
    {
        if (!isset($this->coveredLines[$file])) {
            $this->coveredLines[$file] = [];
        }

        $this->coveredLines[$file][] = $line;
    }

    protected function matchLines(string $fileName, string $matchedFile)
    {
        foreach ($this->cache->diff[$fileName] as $line) {
            $messages = $this->fileChecker->getErrorsOnLine($matchedFile, $line);

            if (is_null($messages)) {
                continue;
            }

            if (count($messages) == 0) {
                $this->addCoveredLine($fileName, $line);
                continue;
            }


            $this->addUnCoveredLine(
                $fileName,
                $line,
                $messages
            );
        }
    }

    protected function addCoveredFile(string $file)
    {
        foreach ($this->cache->diff[$file] as $line) {
            $this->addCoveredLine($file, $line);
        }
    }

    protected function addUnCoveredFile(string $file)
    {
        foreach ($this->cache->diff[$file] as $line) {
            $this->addUnCoveredLine($file, $line, ['No Cover']);
        }
    }

    protected function getDiff(): array
    {
        if (empty($this->cache->diff)) {
            $this->cache->diff = $this->diff->getChangedLines();
        }

        return $this->cache->diff;
    }

    protected function handleFileNotFound(string $file)
    {
        $unMatchedFile = $this->fileChecker->handleNotFoundFile();

        if ($unMatchedFile === true) {
            $this->addCoveredFile($file);
        }

        if ($unMatchedFile === false) {
            $this->addUnCoveredFile($file);
        }
    }

    protected function findFile(string $file, array $coveredFiles): string
    {
        try {
            return $this->matcher->match($file, $coveredFiles);
        } catch (Exceptions\FileNotFound $e) {
            $this->handleFileNotFound($file);
            return '';
        }
    }
}
