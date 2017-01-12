<?php
namespace exussum12\CoverageChecker\FileMatchers;

use exussum12\CoverageChecker\FileMatcher;
use exussum12\CoverageChecker\Exceptions\FileNotFound;

/**
 * Class FileMapper
 * @package exussum12\CoverageChecker\FileMatchers
 */
class FileMapper implements FileMatcher
{
    /**
     * @var string
     */
    protected $originalPath;
    /**
     * @var string
     */
    protected $newPath;

    /**
     * FileMapper constructor.
     * @param string $originalPath
     * @param string $newPath
     */
    public function __construct($originalPath, $newPath)
    {
        $this->originalPath = $originalPath;
        $this->newPath = $newPath;
    }

    /**
     * {@inheritdoc}
     */
    public function match($needle, array $haystack)
    {
        foreach ($haystack as $file) {
            if ($this->checkMapping($file, $needle)) {
                return $file;
            }
        }

        throw new FileNotFound();
    }

    /**
     * @param string $file
     * @param string $needle
     * @return bool
     */
    private function checkMapping($file, $needle)
    {
        return $file == str_replace(
            $this->originalPath,
            $this->newPath,
            $needle
        );
    }
}
