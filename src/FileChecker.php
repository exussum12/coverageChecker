<?php
namespace exussum12\CoverageChecker;

interface FileChecker
{
    public function getLines();

    public function isValidLine($file, $lineNumber);
}
