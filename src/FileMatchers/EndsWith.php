<?php
namespace exussum12\CoverageChecker\FileMatchers;

use exussum12\CoverageChecker\FileMatcher;
use exussum12\CoverageChecker\Exceptions\FileNotFound;

class EndsWith implements FileMatcher
{

    public function match($needle, array $haystack)
    {
        foreach ($haystack as $file) {
            if ($this->endsWith($file, $needle)) {
                return $file;
            }
        }

        throw new FileNotFound();
    }

    protected function endsWith($haystack, $needle)
    {
        $length = strlen($needle);

        return (substr($haystack, -$length) === $needle);
    }
}
