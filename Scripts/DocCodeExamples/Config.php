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

use PHPCSDevTools\Scripts\Utils\HelpTextFormatter;
use PHPCSDevTools\Scripts\Utils\Writer;

/**
 * Process command line arguments for the DocCodeExamples script.
 *
 * ---------------------------------------------------------------------------------------------
 * This class is not part of the public API. Backward compatibility is not guaranteed.
 * ---------------------------------------------------------------------------------------------
 *
 * @since 2.0.0
 */
class Config
{

    /**
     * Writer for sending output.
     *
     * @var \PHPCSDevTools\Scripts\Utils\Writer
     */
    private $writer;

    /**
     * The path to the project root directory.
     *
     * @var string
     */
    protected $projectRoot = '';

    /**
     * The target paths to examine.
     *
     * @var array<int, string>
     */
    protected $targetPaths = [];

    /**
     * The directories to exclude from the search.
     *
     * @var array<int, string>
     */
    protected $excludedDirs = [];

    /**
     * List of sniffs to ignore.
     *
     * @var array<int, string>
     */
    protected $ignoredSniffs = [];

    /**
     * Whether or not to execute the check.
     *
     * @var bool
     */
    protected $executeCheck = true;

    /**
     * Whether or not to show colored output.
     *
     * This is automatically detected.
     *
     * @var bool
     */
    protected $showColored;

    /**
     * Help texts.
     *
     * @var array<string, array<array<string, string>>>
     */
    private $helpTexts = [
        // phpcs:disable Generic.Files.LineLength.TooLong
        'Usage'   => [
            ['text' => 'phpcs-check-doc-examples'],
            ['text' => 'phpcs-check-doc-examples [--exclude=<dir>] [--ignore-sniffs=<sniff1,sniff2>] [directories|files]'],
        ],
        'Options' => [
            [
                'arg'  => 'directories|files',
                'desc' => 'One or more specific directories or files to examine. Defaults to the directory from which the script is run.',
            ],
            [
                'arg'  => '--exclude=<dir1,dir2>',
                'desc' => 'Comma-delimited list of relative paths of directories to exclude from the scan.',
            ],
            [
                'arg'  => '--ignore-sniffs=<sniff1,sniff2>',
                'desc' => 'Comma-delimited list of sniffs to ignore.',
            ],
            [
                'arg'  => '--help',
                'desc' => 'Print this help.',
            ],
            [
                'arg'  => '-V, --version',
                'desc' => 'Display the current version of this script.',
            ],
            [
                'arg'  => '--colors',
                'desc' => 'Enable colors in console output. (disables auto detection of color support)',
            ],
            [
                'arg'  => '--no-colors',
                'desc' => 'Disable colors in console output.',
            ],
        ],
        // phpcs:enable
    ];

    /**
     * List of supported named arguments.
     *
     * @var array<string>
     */
    private $supportedArgs = [
        '--exclude=',
        '--ignore-sniffs=',
        '--help',
        '-V',
        '--version',
        '--colors',
        '--no-colors',
    ];

    /**
     * Constructor.
     *
     * @param \PHPCSDevTools\Scripts\Utils\Writer $writer Writer for sending output.
     */
    public function __construct(Writer $writer)
    {
        $this->writer = $writer;
        $this->processCliCommand();
    }

    /**
     * Get the value of a property.
     *
     * @param string $propertyName The name of the property to retrieve.
     *
     * @return mixed The value of the property, or null if it doesn't exist.
     */
    public function getProperty(string $propertyName)
    {
        if (isset($this->{$propertyName}) === false) {
            throw new \RuntimeException(sprintf('ERROR: Property "%s" does not exist', $propertyName));
        }

        return $this->{$propertyName};
    }

    /**
     * Retrieve the version number of this script.
     *
     * @return string
     */
    public function getVersion()
    {
        $text  = 'PHPCSDevTools: XML documentation code examples checker version ';
        $text .= \file_get_contents(__DIR__ . '/../../VERSION');
        $text .= \PHP_EOL . 'by PHPCSDevTools Contributors' . \PHP_EOL . \PHP_EOL;

        return $text;
    }

    /**
     * Process the received command arguments.
     *
     * @return void
     */
    private function processCliCommand()
    {
        $this->projectRoot = Helper::normalizePath(\getcwd());
        $args              = $_SERVER['argv'];

        // Remove the call to the script itself.
        \array_shift($args);

        $argsFlipped = \array_flip($args);

        if (isset($argsFlipped['--no-colors'])) {
            $this->showColored = false;
        } elseif (isset($argsFlipped['--colors'])) {
            $this->showColored = true;
        } else {
            $this->showColored = HelpTextFormatter::isColorSupported();
        }

        if (isset($argsFlipped['--help'])) {
            $helpText = HelpTextFormatter::format($this->helpTexts, $this->showColored);
            $this->writer->toStdout($this->getVersion());
            $this->writer->toStdout($helpText);
            $this->executeCheck = false;
            return;
        }

        if (isset($argsFlipped['-V']) || isset($argsFlipped['--version'])) {
            $this->writer->toStdout($this->getVersion());
            $this->executeCheck = false;
            return;
        }

        foreach ($args as $arg) {
            if (\strpos($arg, '-') === 0 && $this->isUnsupportedNamedArgument($arg) === true) {
                throw new \RuntimeException("ERROR: Unsupported argument $arg");
            }

            if (\strpos($arg, '--exclude=') === 0) {
                $this->excludedDirs = $this->processExcludeArgument($arg);
                continue;
            }

            if (\strpos($arg, '--ignore-sniffs=') === 0) {
                $this->ignoredSniffs = $this->processIgnoreSniffsArgument($arg);
                continue;
            }

            if (isset($arg[0]) && $arg[0] !== '-') {
                $this->targetPaths[] = $this->processTargetPathArgument($arg);
            }
        }

        if (empty($this->targetPaths)) {
            // If the user didn't provide a path, use the directory from which the script was run.
            $this->targetPaths[] = $this->projectRoot;
        }
    }

    /**
     * Check if a named argument is unsupported.
     *
     * @param string $arg The argument to check.
     *
     * @return bool True if the argument is unsupported, false otherwise.
     */
    private function isUnsupportedNamedArgument(string $arg): bool
    {
        foreach ($this->supportedArgs as $supportedArg) {
            if (\strpos($arg, $supportedArg) === 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * Process the --exclude argument.
     *
     * @param string $arg The argument to process.
     *
     * @return array<string>
     */
    protected function processExcludeArgument(string $arg): array
    {
        $excludeString = \substr($arg, 10);
        if (empty($excludeString)) {
            return [];
        }

        $excludeString = \trim($excludeString, '"\''); // Strip potential quotes.
        $excludedDirs  = \explode(',', $excludeString);

        $excludedDirs = \array_map(
            function ($dir) {
                $dir = \trim($dir, '/');
                return Helper::normalizePath($dir);
            },
            $excludedDirs
        );

        return $excludedDirs;
    }

    /**
     * Process the --ignore-sniffs argument.
     *
     * @param string $arg The argument to process.
     *
     * @return array<string>
     */
    protected function processIgnoreSniffsArgument(string $arg): array
    {
        $ignoredSniffs = \substr($arg, 16);
        if (empty($ignoredSniffs)) {
            return [];
        }

        $ignoredSniffs = \trim($ignoredSniffs, '"\'');
        $ignoredSniffs = \explode(',', $ignoredSniffs);

        return $ignoredSniffs;
    }

    /**
     * Process the target path argument.
     *
     * @param string $arg The argument to process.
     *
     * @return string
     * @throws \RuntimeException If the path does not exist.
     */
    protected function processTargetPathArgument(string $arg): string
    {
        $realpath = \realpath($arg);

        if ($realpath === false) {
            throw new \RuntimeException(\sprintf('ERROR: Target path %s does not exist', $arg));
        }

        return Helper::normalizePath($realpath);
    }
}
