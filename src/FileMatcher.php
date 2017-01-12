<?php
namespace exussum12\CoverageChecker;

interface FileMatcher
{
    /**
     * @param string $needle file to search for
     * @param string[] $haystack list of potential file matches
     *
     * @return string the matched file
     * @throws FileNotFound
     */
    public function match($needle, array $haystack);
}
