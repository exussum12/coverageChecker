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

    public function __construct(string $originalPath, string $newPath)
    {
        $this->originalPath = $originalPath;
        $this->newPath = $newPath;
    }

    /**
     * {@inheritdoc}
     */
    public function match(string $needle, array $haystack): string
    {
        foreach ($haystack as $file) {
            if ($this->checkMapping($file, $needle)) {
                return $file;
            }
        }

        throw new FileNotFound();
    }

    private function checkMapping(string $file, string $needle): bool
    {
        return $file == str_replace(
            $this->originalPath,
            $this->newPath,
            $needle
        );
    }
}
