<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\FeatureComplete;

use PHPCSDevTools\Scripts\Utils\Writer;
use RuntimeException;

/**
 * Process command line arguments for the sniff completeness check.
 *
 * ---------------------------------------------------------------------------------------------
 * This class is not part of the public API. Backward compatibility is not guaranteed.
 * ---------------------------------------------------------------------------------------------
 *
 * @since 2.0.0
 */
final class Config
{

    /**
     * Max width for help text.
     *
     * @var int
     */
    const MAX_WIDTH = 80;

    /**
     * Margin for help options.
     *
     * @var string
     */
    const LEFT_MARGIN = '  ';

    /**
     * Writer for sending output.
     *
     * @var \PHPCSDevTools\Scripts\Utils\Writer
     */
    private $writer;

    /**
     * The root directory of the project.
     *
     * @var string
     */
    private $projectRoot = '';

    /**
     * Whether or not to check the documentation completeness.
     *
     * To disable checking for documentation, pass `--no-docs` on the command line
     * when calling the script or use "quiet" mode by passing `-q`.
     *
     * @var bool
     */
    protected $checkDocs = true;

    /**
     * Whether or not to check for orphaned files.
     *
     * To disable checking for orphaned files, pass `--no-orphans` on the command line
     * when calling the script or use "quiet" mode by passing `-q`.
     *
     * @var bool
     */
    protected $checkOrphans = true;

    /**
     * Whether or not to show progress.
     *
     * To disable showing progress, pass `--no-progress` on the command line
     * when calling the script.
     *
     * @var bool
     */
    private $showProgress = true;

    /**
     * Whether or not to show colored output.
     *
     * This will be automatically detected if not set from the command-line.
     *
     * @var bool
     */
    private $showColored;

    /**
     * Verbosity level.
     *
     * @var int
     */
    private $verbose = 0;

    /**
     * The target directories to examine.
     *
     * @var array<string>
     */
    private $targetDirs = [];

    /**
     * Directories to exclude from the scan.
     *
     * @var array<string>
     */
    private $excludedDirs = [
        'vendor',
    ];

    /**
     * Whether or not to execute the completeness check.
     *
     * @var bool
     */
    private $executeCheck = true;

    /**
     * Help texts.
     *
     * @var array
     */
    private $helpTexts = [
        // phpcs:disable Generic.Files.LineLength.TooLong
        'Usage'   => [
            ['text' => 'phpcs-check-feature-completeness'],
            ['text' => 'phpcs-check-feature-completeness [-q] [--exclude=<dir>] [directories]'],
        ],
        'Options' => [
            [
                'arg'  => 'directories <dir>',
                'desc' => 'One or more specific directories to examine. Defaults to the directory from which the script is run.',
            ],
            [
                'arg'  => '-q, --quiet',
                'desc' => 'Turn off warnings for missing documentation and orphaned files. Equivalent to running with "--no-docs --no-orphans".',
            ],
            [
                'arg'  => '--exclude=<dir1,dir2>',
                'desc' => 'Comma-delimited list of (relative) directories to exclude from the scan. Defaults to excluding the /vendor/ directory.',
            ],
            [
                'arg'  => '--no-docs',
                'desc' => 'Disable missing documentation check.',
            ],
            [
                'arg'  => '--no-orphans',
                'desc' => 'Disable orphaned files check.',
            ],
            [
                'arg'  => '--no-progress',
                'desc' => 'Disable progress in console output.',
            ],
            [
                'arg'  => '--colors',
                'desc' => 'Enable colors in console output. (disables auto detection of color support)',
            ],
            [
                'arg'  => '--no-colors',
                'desc' => 'Disable colors in console output.',
            ],
            [
                'arg'  => '-v',
                'desc' => 'Verbose mode.',
            ],
            [
                'arg'  => '-h, --help',
                'desc' => 'Print this help.',
            ],
            [
                'arg'  => '-V, --version',
                'desc' => 'Display the current version of this script.',
            ],
        ],
        // phpcs:enable
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

        if ($this->executeCheck === true && empty($this->targetDirs)) {
            // If the user didn't provide a path, use the directory from which the script was run.
            $this->targetDirs[] = $this->projectRoot;
        }
    }

    /**
     * Retrieve value of private properties.
     *
     * @param string $name Property name.
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->$name)
            && $name !== 'helpTexts'
            && $name !== 'writer'
        ) {
            return $this->$name;
        }

        return null;
    }

    /**
     * Check whether a (private) property is set.
     *
     * @param string $name Property name.
     *
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->$name) && $name !== 'helpTexts' && $name !== 'writer';
    }

    /**
     * (Re-)Setting a property on this class is not allowed.
     *
     * - Prevent dynamic properties being set.
     * - Prevent overruling the value of properties in this class.
     *
     * @param string $name  Property name.
     * @param mixed  $value Property value.
     *
     * @return bool
     */
    public function __set($name, $value)
    {
        throw new RuntimeException(\sprintf('(Re-)Setting property $%s is not allowed', $name));
    }

    /**
     * Unsetting a property on this class is not allowed.
     *
     * @param string $name Property name.
     *
     * @return bool
     */
    public function __unset($name)
    {
        throw new RuntimeException(\sprintf('Unsetting property $%s is not allowed', $name));
    }

    /**
     * Process the received command arguments.
     *
     * @return void
     */
    protected function processCliCommand()
    {
        $args = $_SERVER['argv'];

        // Remove the call to the script itself.
        \array_shift($args);

        $this->projectRoot = \getcwd();

        if (empty($args)) {
            // No options set.
            $this->showColored = $this->isColorSupported();

            return;
        }

        $argsFlipped = \array_flip($args);

        if (isset($argsFlipped['--no-colors'])) {
            $this->showColored = false;
        } elseif (isset($argsFlipped['--colors'])) {
            $this->showColored = true;
        } else {
            $this->showColored = $this->isColorSupported();
        }

        if (isset($argsFlipped['-h'])
            || isset($argsFlipped['--help'])
        ) {
            $this->writer->toStderr($this->getVersion());
            $this->writer->toStdout($this->getHelp());
            $this->executeCheck = false;
            return;
        }

        if (isset($argsFlipped['-V'])
            || isset($argsFlipped['--version'])
        ) {
            $this->writer->toStdout($this->getVersion());
            $this->executeCheck = false;
            return;
        }

        if (isset($argsFlipped['-q'])
            || isset($argsFlipped['--quiet'])
        ) {
            $this->checkDocs    = false;
            $this->checkOrphans = false;
        }

        if (isset($argsFlipped['--no-docs'])) {
            $this->checkDocs = false;
        }

        if (isset($argsFlipped['--no-orphans'])) {
            $this->checkOrphans = false;
        }

        if (isset($argsFlipped['--no-progress'])) {
            $this->showProgress = false;
        }

        if (isset($argsFlipped['-v'])) {
            $this->verbose = 1;
        }

        foreach ($args as $arg) {
            if (\strpos($arg, '--exclude=') === 0) {
                $exclude = \substr($arg, 10);
                if (empty($exclude)) {
                    $this->excludedDirs = [];
                    continue;
                }

                $exclude = \trim($exclude, '"\''); // Strip potential quotes.
                $exclude = \explode(',', $exclude);
                $exclude = \array_map(
                    static function ($subdir) {
                        return \trim($subdir, '/');
                    },
                    $exclude
                );

                $this->excludedDirs = $exclude;
                continue;
            }

            if (isset($arg[0]) && $arg[0] !== '-') {
                // The user must have set a path to search. Let's ensure it is a valid path.
                $realpath = \realpath($arg);

                if ($realpath === false) {
                    throw new RuntimeException(\sprintf('Target path %s does not exist', $arg));
                }

                $this->targetDirs[] = $realpath;
            }
        }
    }

    /**
     * Detect whether or not the CLI supports colored output.
     *
     * @codeCoverageIgnore
     *
     * @return bool
     */
    protected function isColorSupported()
    {
        // Windows.
        if (\DIRECTORY_SEPARATOR === '\\') {
            if (\getenv('ANSICON') !== false || \getenv('ConEmuANSI') === 'ON') {
                return true;
            }

            if (\function_exists('sapi_windows_vt100_support')) {
                // phpcs:ignore PHPCompatibility.FunctionUse.NewFunctions.sapi_windows_vt100_supportFound
                return @\sapi_windows_vt100_support(\STDOUT);
            }

            return false;
        }

        if (\getenv('GITHUB_ACTIONS')) {
            return true;
        }

        // Linux/MacOS.
        if (\function_exists('posix_isatty')) {
            return @\posix_isatty(\STDOUT);
        }

        return false;
    }

    /**
     * Retrieve the version number of this script.
     *
     * @return string
     */
    public function getVersion()
    {
        $text  = 'PHPCSDevTools: Sniff feature completeness checker version ';
        $text .= \file_get_contents(__DIR__ . '/../../VERSION');
        $text .= \PHP_EOL . 'by Juliette Reinders Folmer' . \PHP_EOL . \PHP_EOL;

        return $text;
    }

    /**
     * Retrieve usage instructions.
     *
     * @return string
     */
    private function getHelp()
    {
        $output = '';
        foreach ($this->helpTexts as $section => $options) {
            $longestOptionLength = 0;
            foreach ($options as $option) {
                if (isset($option['arg'])) {
                    $longestOptionLength = \max($longestOptionLength, \strlen($option['arg']));
                }
            }

            if ($this->showColored === true) {
                $output .= "\033[33m{$section}:\033[0m" . \PHP_EOL;
            } else {
                $output .= "{$section}:" . \PHP_EOL;
            }

            $descWidth = (self::MAX_WIDTH - ($longestOptionLength + 1 + \strlen(self::LEFT_MARGIN)));
            $descBreak = \PHP_EOL . self::LEFT_MARGIN . \str_pad(' ', ($longestOptionLength + 1));

            foreach ($options as $option) {
                if (isset($option['text'])) {
                    $text = $option['text'];
                    if ($this->showColored === true) {
                        $text = \preg_replace('`(\[[^\]]+\])`', "\033[36m" . '$1' . "\033[0m", $text);
                    }
                    $output .= self::LEFT_MARGIN . $text . \PHP_EOL;
                }

                if (isset($option['arg'])) {
                    $arg = \str_pad($option['arg'], $longestOptionLength);
                    if ($this->showColored === true) {
                        $arg = \preg_replace('`(<[^>]+>)`', "\033[0m\033[36m" . '$1', $arg);
                        $arg = "\033[32m{$arg}\033[0m";
                    }

                    $descText = \wordwrap($option['desc'], $descWidth, $descBreak);
                    $desc     = \explode('. ', $option['desc']);
                    if (\count($desc) > 1) {
                        $descText = '';
                        foreach ($desc as $key => $sentence) {
                            $descText .= ($key === 0) ? '' : $descBreak;
                            $descText .= \wordwrap($sentence, $descWidth, $descBreak);
                            $descText  = \rtrim($descText, '.') . '.';
                        }
                    }

                    $output .= self::LEFT_MARGIN . $arg . ' ' . $descText . \PHP_EOL;
                }
            }

            $output .= \PHP_EOL;
        }

        return $output;
    }
}
