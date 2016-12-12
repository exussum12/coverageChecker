<?php
namespace exussum12\CoverageChecker;

abstract class DiffLineHandle {
    protected $diffFileState = null;

    public function __construct(diffFileState $diff)
    {
        $this->diffFileState = $diff;
    }

    public abstract function handle($line);

    public abstract static function isValid($line);
}
