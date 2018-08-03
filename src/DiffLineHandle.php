<?php
namespace exussum12\CoverageChecker;

abstract class DiffLineHandle
{
    protected $diffFileState;

    public function __construct(DiffFileState $diff)
    {
        $this->diffFileState = $diff;
    }

    /**
     * If the line is valid, this function will run on that file
     */
    abstract public function handle(string $line);

    /**
     * Check if the line is valid in the current context
     */
    abstract public function isValid(string $line);
}
