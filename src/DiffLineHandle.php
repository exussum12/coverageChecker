<?php
namespace exussum12\CoverageChecker;

abstract class DiffLineHandle
{
    protected $diffFileState;

    public function __construct(diffFileState $diff)
    {
        $this->diffFileState = $diff;
    }

    abstract public function handle($line);

    abstract public static function isValid($line);
}
