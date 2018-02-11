<?php
namespace exussum12\CoverageChecker;

use InvalidArgumentException;
use stdClass;

/**
 * Class PhpCsLoader
 * Used for reading json output from phpcs
 * @package exussum12\CoverageChecker
 */
class PhpCsLoader implements FileChecker
{
    /**
     * @var stdClass
     */
    protected $json;
    /**
     * @var array
     */
    protected $invalidLines;

    /**
     * @var array
     */

    protected $failOnTypes = [
        'ERROR',
    ];

    /**
     * @var array
     */
    protected $wholeFileErrors = [
        'PSR1.Files.SideEffects.FoundWithSymbols',
        'Generic.Files.LineEndings.InvalidEOLChar',
    ];


    /**
     * @var array
     */
    protected $invalidFiles = [];

    /**
     * PhpCsLoader constructor.
     * @param string $filePath the file path to the json output from phpcs
     */
    public function __construct($filePath)
    {
        $this->json = json_decode(file_get_contents($filePath));
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException(
                "Can't Parse phpcs json - " . json_last_error_msg()
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function parseLines()
    {
        $this->invalidLines = [];
        foreach ($this->json->files as $fileName => $file) {
            foreach ($file->messages as $message) {
                $this->addInvalidLine($fileName, $message);
            }
        }

        return array_merge(
            array_keys($this->invalidLines),
            array_keys($this->invalidFiles)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorsOnLine($file, $lineNumber)
    {
        $errors = [];
        if (!empty($this->invalidFiles[$file])) {
            $errors = $this->invalidFiles[$file];
        }

        if (!empty($this->invalidLines[$file][$lineNumber])) {
             $errors = array_merge($errors, $this->invalidLines[$file][$lineNumber]);
        }

        return $errors;
    }

    /**
     * @param string $file
     * @param stdClass $message
     */
    protected function addInvalidLine($file, $message)
    {
        if (!in_array($message->type, $this->failOnTypes)) {
            return;
        }
        $line = $message->line;

        if (!isset($this->invalidLines[$file][$line])) {
            $this->invalidLines[$file][$line] = [];
        }

        $this->invalidLines[$file][$line][] = $message->message;

        if (in_array($message->source, $this->wholeFileErrors)) {
            $this->invalidFiles[$file][] = $message->message;
        }
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
        return 'Parses the json report format of phpcs, this mode ' .
            'only reports errors as violations';
    }
}
