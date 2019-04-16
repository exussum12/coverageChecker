<?php
namespace exussum12\CoverageChecker\FileMatchers;

use exussum12\CoverageChecker\FileMatcher;
use exussum12\CoverageChecker\Exceptions\FileNotFound;

/**
 * Class EndsWith
 * @package exussum12\CoverageChecker\FileMatchers
 */
class EndsWith implements FileMatcher
{

    /**
     * {@inheritdoc}
     */
    public function match(string $needle, array $haystack): string
    {
        foreach ($haystack as $file) {
            if ($this->fileEndsWith($file, $needle)) {
                return $file;
            }
        }

        throw new FileNotFound();
    }

    /**
     * Find if two strings end in the same way
     */
    protected function fileEndsWith(string $haystack, string $needle): bool
    {
        $length = strlen($needle);
        if (strlen($haystack) < $length) {
            return $this->fileEndsWith($needle, $haystack);
        }

        $haystack = str_replace('\\', '/', $haystack);
        $needle = str_replace('\\', '/', $needle);
        
        return (substr($haystack, -$length) === $needle);
    }
}
