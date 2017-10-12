<?php
namespace exussum12\CoverageChecker;

use InvalidArgumentException;
use stdClass;

/**
 * Class HumbugLoader
 * Used for reading json output from humbug
 * @package exussum12\CoverageChecker
 */
class HumbugLoader implements FileChecker
{
    /**
     * @var string
     */
    protected $json;
    /**
     * @var array
     */
    protected $invalidLines;

    protected $errorMethods = [
        'errored',
        'escaped',
    ];

    /**
     * HumbugLoader constructor.
     * @param string $filePath the file path to json output of humbug
     */
    public function __construct($filePath)
    {
        $this->json = json_decode(file_get_contents($filePath));
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException(
                "Can't Parse Humbug json - " . json_last_error_msg()
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getLines()
    {
        $this->invalidLines = [];
        foreach ($this->errorMethods as $failures) {
            foreach ($this->json->$failures as $errors) {
                $fileName = $errors->file;
                $lineNumber = $errors->line;
                $error = "Failed on $failures check";
                if (!empty($errors->diff)) {
                    $error .= "\nDiff:\n" . $errors->diff;
                }

                $this->invalidLines[$fileName][$lineNumber] = $error;
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
        return 'Parses the json report format of humbug (mutation testing)';
    }
}
