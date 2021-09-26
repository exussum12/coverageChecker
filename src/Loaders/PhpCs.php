<?php
namespace exussum12\CoverageChecker\Loaders;

use InvalidArgumentException;
use exussum12\CoverageChecker\CodeLimits;
use exussum12\CoverageChecker\Exceptions\FileNotFound;
use exussum12\CoverageChecker\FileChecker;
use exussum12\CoverageChecker\FileParser;
use stdClass;

/**
 * Class PhpCs
 * Used for reading json output from phpcs
 * @package exussum12\CoverageChecker
 */
class PhpCs implements FileChecker
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

    protected $lookupErrorPrefix = [
        'Squiz.Commenting.FileComment',
        'Squiz.Commenting.ClassComment',
        'Squiz.Commenting.FunctionComment',
    ];

    protected $functionIgnoreComments = [
        'Squiz.Commenting.FunctionComment.ParamCommentFullStop'
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
     * @var array
     */
    protected $invalidRanges = [];

    /**
     * @var FileParser[]
     */
    protected $parsedFiles = [];

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
    public function parseLines(): array
    {
        $this->invalidLines = [];
        foreach ($this->json->files as $fileName => $file) {
            foreach ($file->messages as $message) {
                $this->addInvalidLine($fileName, $message);
            }
        }

        return array_unique(array_merge(
            array_keys($this->invalidLines),
            array_keys($this->invalidFiles),
            array_keys($this->invalidRanges)
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorsOnLine(string $file, int $lineNumber)
    {
        $errors = [];
        if (!empty($this->invalidFiles[$file])) {
            $errors = $this->invalidFiles[$file];
        }

        if (!empty($this->invalidLines[$file][$lineNumber])) {
             $errors = array_merge($errors, $this->invalidLines[$file][$lineNumber]);
        }

        $errors = array_merge($errors, $this->getRangeErrors($file, $lineNumber));

        return $errors;
    }

    protected function addInvalidLine(string $file, stdClass $message)
    {
        if (!in_array($message->type, $this->failOnTypes)) {
            return;
        }

        $line = $message->line;

        $error = $this->messageStartsWith($message->source, $this->lookupErrorPrefix);

        if ($error && !in_array($message->source, $this->functionIgnoreComments, true)) {
            $this->handleLookupError($file, $message, $error);
            return;
        }

        if (!isset($this->invalidLines[$file][$line])) {
            $this->invalidLines[$file][$line] = [];
        }

        $this->invalidLines[$file][$line][] = $message->message;

        if (in_array($message->source, $this->wholeFileErrors)) {
            $this->invalidFiles[$file][] = $message->message;
        }
    }

    /**
     * @return bool|string
     */
    protected function messageStartsWith(string $message, array $list)
    {
        foreach ($list as $item) {
            if (strpos($message, $item) === 0) {
                return $item;
            }
        }
        return false;
    }

    protected function handleLookupError($file, $message, $error)
    {
        if ($error == 'Squiz.Commenting.FileComment') {
            $this->invalidFiles[$file][] = $message->message;
        }
        try {
            $fileParser = $this->getFileParser($file);
            $lookup = $this->getMessageRanges($error, $fileParser);

            $this->addRangeError($file, $lookup, $message);
        } catch (FileNotFound $exception) {
            error_log("Can't find file, may have missed an error");
        }
    }

    protected function getFileParser($filename)
    {
        if (!isset($this->parsedFiles[$filename])) {
            if (!file_exists($filename)) {
                throw new FileNotFound();
            }

            $this->parsedFiles[$filename] = new FileParser(
                file_get_contents($filename)
            );
        }

        return $this->parsedFiles[$filename];
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
    public static function getDescription(): string
    {
        return 'Parses the json report format of phpcs, this mode ' .
            'only reports errors as violations';
    }

    /**
     * @param string $file
     * @param CodeLimits[] $lookup
     * @param stdClass $message
     */
    protected function addRangeError($file, $lookup, $message)
    {
        $line = $message->line;
        foreach ($lookup as $limit) {
            if ($line >= $limit->getStartLine() && $line <= $limit->getEndLine()) {
                $this->invalidRanges[$file][] = [
                    'from' => $limit->getStartLine(),
                    'to' => $limit->getEndLine(),
                    'message' => $message->message,
                ];
            }
        }
    }

    /**
     * @param string $error
     * @param FileParser $fileParser
     * @return mixed
     */
    protected function getMessageRanges($error, $fileParser)
    {
        if ($error == 'Squiz.Commenting.ClassComment') {
            return $fileParser->getClassLimits();
        }

        return $fileParser->getFunctionLimits();
    }

    /**
     * @param string $file
     * @param int $lineNumber
     * @return array errors on the line
     */
    protected function getRangeErrors($file, $lineNumber)
    {
        $errors = [];

        if (!empty($this->invalidRanges[$file])) {
            foreach ($this->invalidRanges[$file] as $invalidRange) {
                $inRange = $lineNumber >= $invalidRange['from'] &&
                    $lineNumber <= $invalidRange['to'];
                if ($inRange) {
                    $errors[] = $invalidRange['message'];
                }
            }
        }

        return $errors;
    }
}
