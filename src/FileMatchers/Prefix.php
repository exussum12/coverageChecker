<?php
namespace exussum12\CoverageChecker\FileMatchers;

use exussum12\CoverageChecker\FileMatcher;
use exussum12\CoverageChecker\Exceptions\FileNotFound;

/**
 * Class Prefix
 * @package exussum12\CoverageChecker\FileMatchers
 */
class Prefix implements FileMatcher
{
    /**
     * @var string
     */
    protected $prefix;

    /**
     * Prefix constructor.
     * @param string $prefix
     */
    public function __construct(string $prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * {@inheritdoc}
     */
    public function match(string $needle, array $haystack): string
    {
        foreach ($haystack as $file) {
            if ($file == $this->prefix . $needle) {
                return $file;
            }
        }

        throw new FileNotFound();
    }
}
