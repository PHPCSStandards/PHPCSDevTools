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
     * The root directory of the project.
     *
     * @var string
     */
    private $projectRoot = '';

    /**
     * Whether to use "quiet" mode.
     *
     * This will silence all warnings, but still show the errors.
     *
     * To enable "quiet" mode, pass `-q` on the command line when calling
     * the script.
     *
     * @var bool
     */
    private $quietMode = false;

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
     * @var array
     */
    private $targetDirs = [];

    /**
     * Directories to exclude from the scan.
     *
     * @var array
     */
    private $excludedDirs = [
        'vendor',
    ];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->processCliCommand();

        if (empty($this->targetDirs)) {
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
        if (isset($this->$name)) {
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
        return isset($this->$name);
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

        if (isset($argsFlipped['-h'])
            || isset($argsFlipped['--help'])
        ) {
            $this->showHelp();
            exit(0);
        }

        if (isset($argsFlipped['-V'])
            || isset($argsFlipped['--version'])
        ) {
            $this->showVersion();
            exit(0);
        }

        if (isset($argsFlipped['-q'])
            || isset($argsFlipped['--quiet'])
        ) {
            $this->quietMode = true;
        }

        if (isset($argsFlipped['--no-progress'])) {
            $this->showProgress = false;
        }

        if (isset($argsFlipped['--no-colors'])) {
            $this->showColored = false;
        } elseif (isset($argsFlipped['--colors'])) {
            $this->showColored = true;
        } else {
            $this->showColored = $this->isColorSupported();
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

            if ($arg[0] !== '-') {
                // The user must have set a path to search. Let's ensure it is a valid path.
                $realpath = \realpath($arg);

                if ($realpath !== false) {
                    $this->targetDirs[] = $realpath;
                }
            }
        }
    }

    /**
     * Detect whether or not the CLI supports colored output.
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

        // Linux/MacOS.
        if (\function_exists('posix_isatty')) {
            return @\posix_isatty(\STDOUT);
        }

        return false;
    }

    /**
     * Display the version number of this script.
     *
     * @return void
     */
    public function showVersion()
    {
        echo 'PHPCSDevTools: Sniff feature completeness checker version ';
        include __DIR__ . '/../../VERSION';
        echo \PHP_EOL,
            'by Juliette Reinders Folmer', \PHP_EOL, \PHP_EOL;
    }

    /**
     * Display usage instructions.
     *
     * @return void
     */
    private function showHelp()
    {
        $this->showVersion();

        echo 'Usage:', \PHP_EOL,
            '    phpcs-check-feature-completeness', \PHP_EOL,
            '    phpcs-check-feature-completeness [-q] [--exclude=<dir>] [directories]', \PHP_EOL;

        echo \PHP_EOL,
            'Options:', \PHP_EOL,
            '    directories   One or more specific directories to examine.', \PHP_EOL,
            '                  Defaults to the directory from which the script is run.', \PHP_EOL,
            '    -q, --quiet   Turn off warnings for missing documentation.', \PHP_EOL,
            '    --exclude     Comma-delimited list of (relative) directories to exclude', \PHP_EOL,
            '                  from the scan.', \PHP_EOL,
            '                  Defaults to excluding the /vendor/ directory.', \PHP_EOL,
            '    --no-progress Disable progress in console output.', \PHP_EOL,
            '    --colors      Enable colors in console output.', \PHP_EOL,
            '                  (disables auto detection of color support)', \PHP_EOL,
            '    --no-colors   Disable colors in console output.', \PHP_EOL,
            '    -v            Verbose mode.', \PHP_EOL,
            '    -h, --help    Print this help.', \PHP_EOL,
            '    -V, --version Display the current version of this script.', \PHP_EOL;
    }
}
