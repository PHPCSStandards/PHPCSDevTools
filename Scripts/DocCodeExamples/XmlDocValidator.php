<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */


namespace PHPCSDevTools\Scripts\DocCodeExamples;

use PHP_CodeSniffer\Config as PHPCSConfig;
use PHP_CodeSniffer\Files\DummyFile;
use PHP_CodeSniffer\Ruleset;
use PHP_CodeSniffer\Util\Standards;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSDevTools\Scripts\Utils\Writer;

/**
 * Class to represent a sniff XML documentation file and validate the code examples.
 */
class XmlDocValidator
{

    /**
     * Path to the XML documentation file.
     *
     * @var string
     */
    public $path;

    /**
     * The standard name extracted from the file path.
     *
     * @var string
     */
    public $standard;

    /**
     * The sniff name in the format Standard.Category.SniffName.
     *
     * @var string
     */
    public $sniff;

    /**
     * CodeBlocksExtractor instance.
     *
     * @var \PHPCSDevTools\Scripts\DocCodeExamples\CodeBlocksExtractor
     */
    private $extractor;

    /**
     * The PHPCS configuration object.
     *
     * @var \PHP_CodeSniffer\Config
     */
    private $phpcsConfig;

    /**
     * The PHPCS ruleset object.
     *
     * @var \PHP_CodeSniffer\Ruleset
     */
    private $phpcsRuleset;

    /**
     * A helper object to write output.
     *
     * @var \PHPCSDevTools\Scripts\Utils\Writer
     */
    private $writer;

    /**
     * Configuration as passed on the command line.
     *
     * @var \PHPCSDevTools\Scripts\DocCodeExamples\Config
     */
    private $config;

    /**
     * Whether the first error message for this XML file has been displayed or not. Used to control
     * when to display a header for the error messages with the XML file name.
     *
     * @var boolean
     */
    private $firstErrorMessageDisplayed = false;

    /**
     * Validates the XML documentation file and sets the class properties.
     *
     * @param string              $xmlDocFile  Path to the XML documentation file.
     * @param CodeBlocksExtractor $extractor   CodeBlocksExtractor instance.
     * @param PHPCSConfig         $phpcsConfig PHPCS configuration object.
     * @param Writer              $writer      A helper object to write output.
     * @param Config              $config      Configuration as passed on the command line.
     *
     * @throws \RuntimeException If the XML file doesn't exist, the file name or directory structure
     *                           is invalid, or the standard is not installed in PHPCS.
 */
    public function __construct(
        string $xmlDocFile,
        CodeBlocksExtractor $extractor,
        PHPCSConfig $phpcsConfig,
        Writer $writer,
        Config $config
    ) {
        if (!\file_exists($xmlDocFile)) {
            throw new \RuntimeException("The XML file \"{$xmlDocFile}\" does not exist.");
        }

        $this->extractor   = $extractor;
        $this->phpcsConfig = $phpcsConfig;
        $this->writer      = $writer;
        $this->config      = $config;
        $this->path        = $xmlDocFile;
        $pathInfo          = \pathinfo($this->path);

        if (\substr($pathInfo['basename'], -12) !== 'Standard.xml') {
            throw new \RuntimeException(
                "ERROR: The XML file \"{$pathInfo['basename']}\" is invalid. File names should end in \"Standard.xml\"."
            );
        }

        // Split the path into directories regardless of which directory separator is used.
        $dirs = \preg_split('`[/\\\\]`', $pathInfo['dirname']);

        if (\count($dirs) < 3 || $dirs[\count($dirs) - 2] !== 'Docs') {
            throw new \RuntimeException(
                'ERROR: Invalid directory structure in the XML file path. Expected: '
                . '{STANDARD_NAME}/Docs/{CATEGORY_NAME}/' . $pathInfo['basename'] . '.'
            );
        }

        $this->standard = $dirs[\count($dirs) - 3];
        $subset         = $dirs[\count($dirs) - 1];
        $this->sniff    = $this->standard . '.' . $subset . '.' . \str_replace('Standard', '', $pathInfo['filename']);

        if (Standards::isInstalledStandard($this->standard) === false) {
            throw new \RuntimeException(
                "ERROR: The standard \"{$this->standard}\" is not installed in PHPCS."
            );
        }
    }

    /**
     * Validates all the code blocks in a given XML documentation file.
     *
     * @return boolean Whether the validation was successful or not.
     */
    public function validate(): bool
    {
        $this->setUpPhpcs();

        $validated = true;

        $codeBlocks        = $this->extractor->extract($this->path);
        $validCodeBlocks   = $codeBlocks['valid'];
        $invalidCodeBlocks = $codeBlocks['invalid'];

        $validPassedValidation   = $this->verifyCodeBlocks($validCodeBlocks, true);
        $invalidPassedValidation = $this->verifyCodeBlocks($invalidCodeBlocks, false);

        if ($validPassedValidation === false || $invalidPassedValidation === false) {
            $validated = false;
        }

        return $validated;
    }

    /**
     * Set up the PHPCS to be able to run the sniff against a code example programmatically.
     *
     * @return void
     */
    private function setUpPhpcs()
    {
        if (\defined('PHP_CODESNIFFER_VERBOSITY') === false) {
            \define('PHP_CODESNIFFER_VERBOSITY', 0);
        }

        if (\defined('PHP_CODESNIFFER_CBF') === false) {
            \define('PHP_CODESNIFFER_CBF', false);
        }

        $this->phpcsConfig->standards = [$this->standard];
        $this->phpcsConfig->sniffs    = [$this->sniff];

        new Tokens(); // @phpstan-ignore new.resultUnused

        $this->phpcsRuleset = new Ruleset($this->phpcsConfig);
    }

    /**
     * Verify code blocks of a given section (valid or invalid).
     *
     * @param array<int, \PHPCSDevTools\Scripts\DocCodeExamples\CodeBlock> $codeBlocks          The code blocks to
     *                                                                                          validate.
     * @param bool                                                         $isExpectedToBeValid Whether the code blocks
     *                                                                                          are expected to be valid
     *                                                                                          or invalid.
     *
     * @return bool
     */
    private function verifyCodeBlocks(array $codeBlocks, bool $isExpectedToBeValid): bool
    {
        $codeBlocksPassedValidation = true;

        foreach ($codeBlocks as $codeBlock) {
            $passedValidation = $this->verifyCodeBlock($codeBlock, $isExpectedToBeValid);

            if ($passedValidation === false && $codeBlocksPassedValidation !== false) {
                $codeBlocksPassedValidation = false;
            }
        }

        return $codeBlocksPassedValidation;
    }

    /**
     * Run a given group of code examples through PHPCS and check if what is returned matches the
     * expectation (whether errors/warnings are expected or not depending on whether the code
     * belong to the valid or invalid section).
     *
     * @param CodeBlock $codeBlock           PHP code example to be checked.
     * @param bool      $isExpectedToBeValid Whether the code example was extracted from the valid
     *                                       or invalid section and thus is expected to produce
     *                                       PHPCS errors or not.
     *
     * @return bool Whether the code block matched the expectation.
     */
    private function verifyCodeBlock(CodeBlock $codeBlock, bool $isExpectedToBeValid): bool
    {
        $codeBlockContent           = $this->maybeAddPhpOpenTag($codeBlock->content);
        $codeBlockBehavesAsExpected = true;

        try {
            // @phpstan-ignore function.resultUnused (maybe a PHPStan false positive?)
            \token_get_all($codeBlockContent, \TOKEN_PARSE);
        } catch (\Throwable $e) {
            $message  = 'There is a syntax error in the code block.' . \PHP_EOL;
            $message .= $e->getMessage() . \PHP_EOL;
            $this->displayErrorMessage(
                $this->prepareCodeBlockErrorMessage($message, $codeBlock)
            );
            $codeBlockBehavesAsExpected = false;
        }

        // Set a unique file name for the DummyFile instance to avoid cache conflicts for
        // sniffs that rely on PHPCSUtils functionality.
        $dummyFileName = md5($this->sniff . $codeBlock->position);
        $fileContents  = 'phpcs_input_file: ' . $dummyFileName . PHP_EOL;
        $fileContents .= $codeBlockContent;

        $file = new DummyFile($fileContents, $this->phpcsRuleset, $this->phpcsConfig);
        $file->process();

        if ($isExpectedToBeValid === true && ($file->getErrorCount() !== 0 || $file->getWarningCount() !== 0)) {
            $message = 'Code block is valid and PHPCS should have returned nothing, ' .
                       'but instead it returned an error.';
            $this->displayErrorMessage(
                $this->prepareCodeBlockErrorMessage($message, $codeBlock)
            );
            $codeBlockBehavesAsExpected = false;
        }

        if ($isExpectedToBeValid === false && ($file->getErrorCount() === 0 && $file->getWarningCount() === 0)) {
            $message = 'Code block is invalid and PHPCS should have returned an error message, ' .
                       'but instead it returned nothing.';
            $this->displayErrorMessage(
                $this->prepareCodeBlockErrorMessage($message, $codeBlock)
            );
            $codeBlockBehavesAsExpected = false;
        }

        return $codeBlockBehavesAsExpected;
    }

    /**
     * Prepare the error message together with the code block title and content.
     *
     * @param string    $message   The error message.
     * @param CodeBlock $codeBlock The code block that caused the error.
     *
     * @return string The error message with additional information added.
     */
    private function prepareCodeBlockErrorMessage(string $message, CodeBlock $codeBlock): string
    {
        $errorPrefix = 'ERROR: ';

        if ($this->config->getProperty('showColored') === true) {
            $errorPrefix = "\033[31m{$errorPrefix}\033[0m";
        }

        $errorMessage  = $errorPrefix . $message . \PHP_EOL;
        $errorMessage .= "Code block title: \"{$codeBlock->title}\"" . \PHP_EOL;
        $errorMessage .= "Code block content: \"{$codeBlock->content}\"" . \PHP_EOL;

        return $errorMessage . \PHP_EOL;
    }

    /**
     * Display an error message. Might include the file name at the top, if it is the first
     * error message for this XML file.
     *
     * @param string $message The error message to display.
     *
     * @return void
     */
    private function displayErrorMessage(string $message)
    {
        if ($this->firstErrorMessageDisplayed === false) {
            $errorHeader = "Errors found while processing {$this->path}";

            if ($this->config->getProperty('showColored') === true) {
                $errorHeader = "\033[31m{$errorHeader}\033[0m";
            }

            $this->writer->toStderr($errorHeader . \PHP_EOL . \PHP_EOL);
        }

        $this->firstErrorMessageDisplayed = true;

        $this->writer->toStderr($message);
    }

    /**
     * Adds a PHP open tag to the beginning of the code block content if it doesn't already have one.
     *
     * @param string $codeBlockContent The code block content to check.
     *
     * @return string
     */
    private function maybeAddPhpOpenTag(string $codeBlockContent): string
    {
        $normalizedCodeBlockContent = \trim(\strtolower($codeBlockContent));

        if (\strpos($normalizedCodeBlockContent, '<?php') === false
            && \strpos($normalizedCodeBlockContent, '<?=') === false
        ) {
            $codeBlockContent = '<?php ' . \PHP_EOL . $codeBlockContent;
        }

        return $codeBlockContent;
    }
}
