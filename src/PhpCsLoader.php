<?php
namespace exussum12\CoverageChecker;

use InvalidArgumentException;

/**
 * Class PhpCsLoader
 * Used for reading json output from phpcs
 * @package exussum12\CoverageChecker
 */
class PhpCsLoader implements FileChecker
{
    /**
     * @var string
     */
    protected $json;
    /**
     * @var array
     */
    protected $invalidLines;

    /**
     * PhpCsLoader constructor.
     * @param $filePath the file path to the json output from phpcs
     */
    public function __construct($filePath)
    {
        $this->json = json_decode(file_get_contents($filePath));
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException();
        }
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function isValidLine($file, $lineNumber)
    {
        return empty($this->invalidLines[$file][$lineNumber]);
    }

    /**
     * @param string $file
     * @param int $line
     * @param string $error
     */
    protected function addInvalidLine($file, $line, $error)
    {
        if (!isset($this->invalidLines[$file][$line])) {
             $this->invalidLines[$file][$line] = [];
        }
        $this->invalidLines[$file][$line][] = $error;
    }
}
