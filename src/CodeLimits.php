<?php
namespace exussum12\CoverageChecker;

class CodeLimits
{
    protected $startLine;
    protected $endLine;

    public function __construct($startLine, $endLine)
    {
        $this->startLine = $startLine;
        $this->endLine = $endLine;
    }

    public function getStartLine()
    {
        return $this->startLine;
    }

    public function getEndLine()
    {
        return $this->endLine;
    }
}
