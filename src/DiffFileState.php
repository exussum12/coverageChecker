<?php
namespace exussum12\CoverageChecker;

class DiffFileState
{
    private $currentPosition;
    private $currentFile;
    private $changeLines = [];
    
    public function setCurrentPosition(int $position)
    {
        $this->currentPosition = $position;
    }

    public function setCurrentFile(string $currentFile)
    {
        $this->currentFile = $currentFile;
    }

    public function addChangeLine()
    {
        if (!isset($this->changeLines[$this->currentFile])) {
            $this->changeLines[$this->currentFile] = [];
        }

        $this->changeLines[$this->currentFile][] = $this->currentPosition;
    }

    public function incrementCurrentPosition()
    {
        $this->currentPosition++;
    }

    public function decrementCurrentPosition()
    {
        $this->currentPosition--;
    }

    public function getChangedLines()
    {
        return $this->changeLines;
    }
}
