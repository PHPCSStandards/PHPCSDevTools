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
use PHPCSDevTools\Scripts\Utils\FileList;
use PHPCSDevTools\Scripts\Utils\Writer;

/**
 * Check that each code example in a collection of sniff XML documentation files is correct.
 *
 * ---------------------------------------------------------------------------------------------
 * This class is not part of the public API. Backward compatibility is not guaranteed.
 * ---------------------------------------------------------------------------------------------
 *
 * @since 2.0.0
 */
class Check
{

    /**
     * Regex used to filter the files down to docs only.
     *
     * @var string
     */
    const DOC_FILTER_REGEX = '`%1$s%2$s.*?/Docs/.+Standard\.xml$`Di';

    /**
     * Configuration as passed on the command line
     *
     * @var \PHPCSDevTools\Scripts\DocCodeExamples\Config
     */
    protected $config;

    /**
     * CodeBlocksExtractor instance.
     *
     * @var \PHPCSDevTools\Scripts\DocCodeExamples\CodeBlocksExtractor
     */
    protected $extractor;

    /**
     * The PHPCS configuration object.
     *
     * @var \PHP_CodeSniffer\Config
     */
    protected $phpcsConfig;

    /**
     * A helper object to write output.
     *
     * @var \PHPCSDevTools\Scripts\Utils\Writer
     */
    protected $writer;

    /**
     * List of all XML files to check.
     *
     * @var array<string>
     */
    protected $xmlFiles = [];

    /**
     * Constructor.
     *
     * @param \PHPCSDevTools\Scripts\DocCodeExamples\Config              $config      Configuration as passed on the
     *                                                                                command line.
     * @param \PHPCSDevTools\Scripts\DocCodeExamples\CodeBlocksExtractor $extractor   CodeBlocksExtractor instance.
     * @param \PHP_CodeSniffer\Config                                    $phpcsConfig PHPCS configuration object.
     * @param \PHPCSDevTools\Scripts\Utils\Writer                        $writer      A helper object to write output.
     */
    public function __construct(
        Config $config,
        CodeBlocksExtractor $extractor,
        PHPCSConfig $phpcsConfig,
        Writer $writer
    ) {
        $this->config      = $config;
        $this->extractor   = $extractor;
        $this->phpcsConfig = $phpcsConfig;
        $this->writer      = $writer;

        // Handle excluded dirs.
        $exclude = '(?!\.git/)';
        if (empty($config->getProperty('excludedDirs')) === false) {
            $excludedDirs = \array_map(
                'preg_quote',
                $config->getProperty('excludedDirs'),
                \array_fill(0, \count($config->getProperty('excludedDirs')), '`')
            );
            $exclude      = '(?!(\.git|' . \implode('|', $excludedDirs) . ')/)';
        }

        $quotedProjectRoot = \preg_quote($config->getProperty('projectRoot') . '/', '`');
        $docsRegex         = \sprintf(self::DOC_FILTER_REGEX, $quotedProjectRoot, $exclude);

        $xmlFilesInDirs     = [];
        $individualXmlFiles = [];

        foreach ($config->getProperty('targetPaths') as $targetPath) {
            if (\is_dir($targetPath)) {
                $xmlFilesInDirs[] = (new FileList($targetPath, $config->getProperty('projectRoot'), $docsRegex))
                    ->getList();
            }

            if (\is_file($targetPath)) {
                $individualXmlFiles[] = \str_replace($config->getProperty('projectRoot'), '', $targetPath);
            }
        }

        if (empty($xmlFilesInDirs) === false) {
            $xmlFilesInDirs = \call_user_func_array('array_merge', $xmlFilesInDirs);
        }

        $this->xmlFiles = array_merge($individualXmlFiles, $xmlFilesInDirs);
        \sort($this->xmlFiles, \SORT_NATURAL);
    }

    /**
     * Run the check for sniff documentation code examples.
     *
     * @return int Exit code for the script.
     */
    public function run(): int
    {
        $this->writer->toStderr($this->config->getVersion());

        $exitCode              = 0;
        $validationFailedCount = 0;

        if (empty($this->xmlFiles)) {
            $message = 'ERROR: No XML documentation files found.';

            if ($this->config->getProperty('showColored') === true) {
                $message = "\033[31m$message\033[0m";
            }

            $this->writer->toStderr($message . PHP_EOL);
            return 1;
        }

        foreach ($this->xmlFiles as $xmlDocFilePath) {
            $xmlDocValidator = $this->getXmlDocValidator($xmlDocFilePath);

            if (\in_array($xmlDocValidator->sniff, $this->config->getProperty('ignoredSniffs'), true)) {
                continue;
            }

            $validationSuccessful = $xmlDocValidator->validate();

            if ($validationSuccessful === false) {
                $validationFailedCount++;
                $exitCode = 1;
            }
        }

        $xmlFilesCount = \count($this->xmlFiles);
        $feedback      = "Checked {$xmlFilesCount} XML documentation files.";

        if ($xmlFilesCount === 1) {
            $feedback = 'Checked 1 XML documentation file.';
        }

        if ($exitCode === 0) {
            $feedback .= ' All code examples are valid.';

            if ($this->config->getProperty('showColored') === true) {
                $feedback = "\033[32m{$feedback}\033[0m";
            }
        } else {
            $feedback .= " Found incorrect code examples in {$validationFailedCount}.";

            if ($this->config->getProperty('showColored') === true) {
                $feedback = "\033[31m{$feedback}\033[0m";
            }
        }

        $this->writer->toStdout($feedback . PHP_EOL);

        return $exitCode;
    }

    /**
     * Retrieves the XML documentation validator object for a given file path.
     *
     * @param string $xmlDocFilePath The relative file path to the XML documentation file.
     *
     * @return \PHPCSDevTools\Scripts\DocCodeExamples\XmlDocValidator The XML documentation validator object.
     */
    protected function getXmlDocValidator(string $xmlDocFilePath): XmlDocValidator
    {
        return new XmlDocValidator(
            $this->config->getProperty('projectRoot') . $xmlDocFilePath,
            $this->extractor,
            $this->phpcsConfig,
            $this->writer,
            $this->config
        );
    }
}
