<?php
namespace exussum12\CoverageChecker;

/**
 * This file gets the file context before the file was changed.
 * ie old -> new, this returns what used to be there
 */
class DiffFileLoaderOldVersion extends DiffFileLoader
{
    protected $diffLines = [
        DiffLineHandle\OldVersion\NewFile::class,
        DiffLineHandle\OldVersion\AddedLine::class,
        DiffLineHandle\OldVersion\RemovedLine::class,
        DiffLineHandle\OldVersion\DiffStart::class,
    ];
}
