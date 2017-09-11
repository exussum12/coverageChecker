<?php
namespace exussum12\CoverageChecker;

/**
 * Class CodeClimateLoader
 * Used for parsing reports in CodeClimate format
 * @package exussum12\CoverageChecker
 */
class CodeClimateLoader implements FileChecker
{
    /**
     * @var string
     */
    protected $file;

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * PhpMdLoader constructor.
     * @param string $file the path to the phan json file
     */
    public function __construct($file)
    {
        $json = $this->convertToJson(file_get_contents($file));
        $this->file = json_decode($json);
    }

    /**
     * {@inheritdoc}
     */
    public function getLines()
    {
        foreach ($this->file as $line) {
            $this->addError($line);
        }

        return $this->errors;
    }

    /**
     * {@inheritdoc}
     */
    public function isValidLine($file, $lineNumber)
    {
        return empty($this->errors[$file][$lineNumber]);
    }

    /**
     * {@inheritdoc}
     */
    public function handleNotFoundFile()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function getDescription()
    {
        return 'Parse codeclimate output';
    }

    private function addError($line)
    {
        $trim = './';
        $fileName = substr($line->location->path, strlen($trim));
        $start = $line->location->lines->begin;
        $end = $line->location->lines->end;
        $message = $line->description;

        for($lineNumber = $start; $lineNumber <= $end; $lineNumber++) {
            $this->errors[$fileName][$lineNumber] = $message;
        }
    }

    private function convertToJson($codeClimateFormat)
    {
        $codeClimateFormat = str_replace("\0", ',', $codeClimateFormat);

        return $codeClimateFormat;
    }
}
