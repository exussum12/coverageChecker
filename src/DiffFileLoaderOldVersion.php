<?php
namespace exussum12\CoverageChecker;

class DiffFileLoaderOldVersion extends DiffFileLoader
{
    protected $diffLines = [
        DiffLineHandle\OldVersion\NewFile::class,
        DiffLineHandle\OldVersion\AddedLine::class,
        DiffLineHandle\OldVersion\RemovedLine::class,
        DiffLineHandle\OldVersion\DiffStart::class,
    ];
}
