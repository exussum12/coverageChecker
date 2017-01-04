<?php
namespace exussum12\CoverageChecker;

use InvalidArgumentException;

class PhpCsLoader implements FileChecker
{
    protected $json;
    protected $invalidLines;
    public function __construct($filePath)
    {
        $this->json = json_decode(file_get_contents($filePath));
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException();
        }
    }

    public function getLines()
    {
        $this->invalidLines = [];
        foreach ($this->json->files as $fileName => $file) {
            foreach ($file->messages as $message) {
                $this->addInvalidLine($fileName, $message->line, $message->message);
            }
        }

        return $this->invalidLines;
    }

    public function isValidLine($file, $lineNumber)
    {
        return empty($this->invalidLines[$file][$lineNumber]);
    }

    protected function addInvalidLine($file, $line, $error)
    {
        if (!isset($this->invalidLines[$file][$line])) {
             $this->invalidLines[$file][$line] = [];
        }
        $this->invalidLines[$file][$line][] = $error;
    }
}
