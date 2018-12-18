<?php
namespace exussum12\CoverageChecker;

use exussum12\CoverageChecker\Exceptions\FileNotFound;

interface FileMatcher
{
    /**
     * @param string $needle file to search for
     * @param string[] $haystack list of potential file matches
     *
     * @return string the matched file
     * @throws FileNotFound
     */
    public function match(string $needle, array $haystack): string;
}
