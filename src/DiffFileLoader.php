<?php
namespace exussum12\CoverageChecker;

use exussum12\CoverageChecker\DiffLineHandle;
use InvalidArgumentException;

class DiffFileLoader
{
    protected $fileLocation;

    protected $diffLines = [
        DiffLineHandle\NewFile::class,
        DiffLineHandle\AddedLine::class,
        DiffLineHandle\RemovedLine::class,
        DiffLineHandle\DiffStart::class,
    ];
    protected $handles = [];
    protected $diff;

    public function __construct($fileName)
    {
        $this->fileLocation = $fileName;
        $this->diff = new DiffFileState();

    }

    public function getChangedLines()
    {
        if ((
            !is_readable($this->fileLocation) &&
            $this->fileLocation !== "php://stdin"
        )) {
            throw new InvalidArgumentException("Can't read file");
        }

        $handle = fopen($this->fileLocation, 'r');

        while (($line = fgets($handle)) !== false) {
            // process the line read.
            $lineHandle = $this->getLineHandle($line);
            $lineHandle->handle($line);
            $this->diff->incrementCurrentPosition();
        }

        fclose($handle);

        return $this->diff->getChangedLines();
    }

    private function getLineHandle($line)
    {
        foreach ($this->diffLines as $lineType) {
            if ($lineType::isValid($line)) {
                return $this->getClass($lineType);
            }
        }
        //not found, Class it as context
        return $this->getClass(DiffLineHandle\ContextLine::class);
    }

    private function getClass($className)
    {
        if (!isset($this->handles[$this->getFileHandleName($className)])) {
            $this->handles[
                $this->getFileHandleName($className)
            ] = new $className($this->diff);
        }

        return $this->handles[$this->getFileHandleName($className)];
    }

    private function getFileHandleName($namespace)
    {
        $namespace = explode('\\', $namespace);
        return end($namespace);
    }
}
