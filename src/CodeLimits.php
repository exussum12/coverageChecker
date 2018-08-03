<?php
namespace exussum12\CoverageChecker;

class CodeLimits
{
    protected $startLine;
    protected $endLine;

    public function __construct(int $startLine, int $endLine)
    {
        $this->startLine = $startLine;
        $this->endLine = $endLine;
    }

    public function getStartLine(): int
    {
        return $this->startLine;
    }

    public function getEndLine(): int
    {
        return $this->endLine;
    }
}
