<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSStandards\Scripts;

/**
 * Check that each sniff is feature complete, i.e. has unit tests and documentation.
 *
 * @since 1.0.0
 */
class CheckSniffCompleteness
{

    /**
     * Regex used to filter the files down to sniffs only.
     *
     * @var string
     */
    const FILTER_REGEX = '`%1$s%2$s.*?/Sniffs/(?!Abstract).+Sniff\.php$`Di';

    /**
     * Whether or not to show progress.
     *
     * To disable showing progress, pass `--no-progress` on the command line
     * when calling the script.
     *
     * @var boolean
     */
    protected $showProgress = true;

    /**
     * Whether to use "quiet" mode.
     *
     * This will silence all warnings, but still show the errors.
     *
     * To enable "quiet" mode, pass `-q` on the command line when calling
     * the script.
     *
     * @var boolean
     */
    protected $quietMode = false;

    /**
     * Whether or not to show colored output.
     *
     * This will be automatically detected if not set from the command-line.
     *
     * @var boolean
     */
    protected $showColored;

    /**
     * Verbosity level.
     *
     * @var int
     */
    protected $verbose = 0;

    /**
     * The target directories to examine.
     *
     * @var array
     */
    protected $targetDirs = [];

    /**
     * The root directory of the project.
     *
     * @var string
     */
    protected $projectRoot = '';

    /**
     * Whether to use "quiet" mode.
     *
     * This will silence all warnings, but still show the errors.
     *
     * To enable "quiet" mode, pass `-q` on the command line when calling
     * the `check-sniff-completeness` script.
     *
     * @var boolean
     */
    protected $excludedDirs = [
        'vendor',
    ];

    /**
     * List of all files in the repo.
     *
     * @var array
     */
    protected $allFiles = [];

    /**
     * List of all sniff files in the repo.
     *
     * @var array
     */
    protected $allSniffs = [];

    /**
     * Search & replace values to convert a sniff file path into a docs file path.
     *
     * Keys are the strings to search for, values the replacement values.
     *
     * @var array
     */
    private $sniffToDoc = [
        '/Sniffs/'  => '/Docs/',
        'Sniff.php' => 'Standard.xml',
    ];

    /**
     * Search & replace values to convert a sniff file path into a unit test file path.
     *
     * Keys are the strings to search for, values the replacement values.
     *
     * @var array
     */
    private $sniffToUnitTest = [
        '/Sniffs/' => '/Tests/',
        'Sniff.'   => 'UnitTest.',
    ];

    /**
     * Possible test case file extensions.
     *
     * @var array
     */
    private $testCaseExtensions = [
        '.inc',
        '.css',
        '.js',
        '.1.inc',
        '.1.css',
        '.1.js',
    ];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->processCliCommand();

        $sep = '/';
        if (empty($this->targetDirs)) {
            // If the user didn't provide a path, use the directory from which the script was run.
            $this->targetDirs[] = $this->projectRoot;
        } else {
            // Handle Windows vs Unix file paths.
            $sep = DIRECTORY_SEPARATOR;
        }

        // Handle excluded dirs.
        $exclude = '(?!\.git/)';
        if (empty($this->excludedDirs) === false) {
            $excludedDirs = array_map(
                'preg_quote',
                $this->excludedDirs,
                array_fill(0, count($this->excludedDirs), '`')
            );
            $exclude      = '(?!(\.git|' . implode('|', $excludedDirs) . ')/)';
        }

        // Prepare the regexes.
        $quotedProjectRoot = preg_quote($this->projectRoot . $sep, '`');
        $allFilesRegex     = str_replace('(?!\.git/)', $exclude, FileList::BASE_REGEX);
        $allFilesRegex     = sprintf($allFilesRegex, $quotedProjectRoot);
        $sniffsRegex       = sprintf(self::FILTER_REGEX, $quotedProjectRoot, $exclude);

        // Get the file lists.
        $allFiles  = [];
        $allSniffs = [];
        foreach ($this->targetDirs as $targetDir) {
            // Get a list of all files in the target directory.
            $allFiles[] = (new FileList($targetDir, $this->projectRoot, $allFilesRegex))->getList();

            // Get a list of all sniffs in the target directory.
            $allSniffs[] = (new FileList($targetDir, $this->projectRoot, $sniffsRegex))->getList();
        }

        $allFiles = call_user_func_array('array_merge', $allFiles);
        sort($allFiles, SORT_NATURAL);
        $this->allFiles = array_flip($allFiles);

        $allSniffs = call_user_func_array('array_merge', $allSniffs);
        sort($allSniffs, SORT_NATURAL);
        $this->allSniffs = $allSniffs;
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
        array_shift($args);

        $this->projectRoot = getcwd();

        if (empty($args)) {
            // No options set.
            $this->showColored = $this->isColorSupported();

            return;
        }

        $argsFlipped = array_flip($args);

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
            if (strpos($arg, '--exclude=') === 0) {
                $exclude = substr($arg, 10);
                if ($exclude === '') {
                    $this->excludedDirs = [];
                    continue;
                }

                $exclude = explode(',', $exclude);
                $exclude = array_map(
                    function ($subdir) {
                        return trim($subdir, '/');
                    },
                    $exclude
                );

                $this->excludedDirs = $exclude;
                continue;
            }

            if ($arg[0] !== '-') {
                // The user must have set a path to search.
                $realpath = realpath($arg);

                if ($realpath !== false) {
                    $this->targetDirs[] = $realpath;
                }
            }
        }
    }

    /**
     * Validate the completeness of the sniffs in the repository.
     *
     * @return void
     */
    public function validate()
    {
        $this->showVersion();

        if ($this->verbose > 0) {
            echo 'Target dir(s):', PHP_EOL,
                '- ' . implode(PHP_EOL . '- ', $this->targetDirs),
                PHP_EOL, PHP_EOL;
        }

        if ($this->isComplete() !== true) {
            exit(1);
        }

        exit(0);
    }

    /**
     * Verify if all files needed for a sniff to be considered complete are available.
     *
     * @return void
     */
    public function isComplete()
    {
        $sniffCount = count($this->allSniffs);
        if ($sniffCount === 0) {
            echo 'No sniffs found.', PHP_EOL;
            return true;
        }

        $docWarning    = 'WARNING: Documentation missing for %s.';
        $testError     = 'ERROR: Unit tests missing for %s.';
        $testCaseError = 'ERROR: Unit test case file missing for %s.';

        if ($this->showColored === true) {
            $docWarning    = str_replace('WARNING', "\033[33mWARNING\033[0m", $docWarning);
            $testError     = str_replace('ERROR', "\033[31mERROR\033[0m", $testError);
            $testCaseError = str_replace('ERROR', "\033[31mERROR\033[0m", $testError);
        }

        $notices      = [];
        $warningCount = 0;
        $errorCount   = 0;
        foreach ($this->allSniffs as $i => $file) {
            if ($this->quietMode === false) {
                $docFile = str_replace(array_keys($this->sniffToDoc), $this->sniffToDoc, $file);
                if (isset($this->allFiles[$docFile]) === false) {
                    $notices[] = sprintf($docWarning, $file);
                    ++$warningCount;
                }
            }

            $testFile = str_replace(array_keys($this->sniffToUnitTest), $this->sniffToUnitTest, $file);
            if (isset($this->allFiles[$testFile]) === false) {
                $notices[] = sprintf($testError, $file);
                ++$errorCount;
            } else {
                $fileFound = false;
                foreach ($this->testCaseExtensions as $extension) {
                    $testCaseFile = str_replace('.php', $extension, $testFile);
                    if (isset($this->allFiles[$testCaseFile]) === true) {
                        $fileFound = true;
                        break;
                    }
                }

                if ($fileFound === false) {
                    $notices[] = sprintf($testCaseError, $file);
                    ++$errorCount;
                }
            }

            // Show progress.
            if ($this->showProgress === true) {
                echo '.';

                $current = ($i + 1);
                if (($current % 60) === 0 || $current === $sniffCount) {
                    $padding = strlen($sniffCount);

                    $filling = '';
                    if ($current === $sniffCount) {
                        $lines = ceil($current / 60);
                        if ($lines > 1) {
                            $filling = str_repeat(' ', (($lines * 60) - $sniffCount));
                        }
                    }

                    echo $filling, ' ', str_pad($current, $padding, ' ', \STR_PAD_LEFT), ' / ', $sniffCount,
                        ' (', str_pad(round(($current / $sniffCount) * 100), 3, ' ', \STR_PAD_LEFT), '%)', PHP_EOL;
                }
            }
        }

        /*
         * Show feedback to the user.
         */
        if (empty($notices) === false) {
            // Show the errors and warnings.
            echo PHP_EOL,
                implode(PHP_EOL, $notices), PHP_EOL,
                PHP_EOL,
                '-----------------------------------------', PHP_EOL,
                sprintf('Found %d errors and %d warnings', $errorCount, $warningCount), PHP_EOL;

            return false;
        } else {
            $feedback = "All $sniffCount sniffs are";
            if ($sniffCount === 1) {
                $feedback = "Found $sniffCount sniff";
            }

            if ($this->quietMode === false) {
                $feedback .= ' accompanied by unit tests and documentation.';
            } else {
                $feedback .= ' accompanied by unit tests.';
            }

            if ($this->showColored === true) {
                $feedback = "\033[32m" . $feedback . "\033[0m";
            }

            echo PHP_EOL, PHP_EOL, $feedback, PHP_EOL;

            return true;
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
        if (DIRECTORY_SEPARATOR === '\\') {
            if (getenv('ANSICON') !== false || getenv('ConEmuANSI') === 'ON') {
                return true;
            }

            if (function_exists('sapi_windows_vt100_support')) {
                // phpcs:ignore PHPCompatibility.FunctionUse.NewFunctions.sapi_windows_vt100_supportFound
                return @sapi_windows_vt100_support(STDOUT);
            }

            return false;
        }

        // Linux/MacOS.
        if (function_exists('posix_isatty')) {
            return @posix_isatty(STDOUT);
        }

        return false;
    }

    /**
     * Display the version number of this script.
     *
     * @return void
     */
    protected function showVersion()
    {
        echo 'PHPCSDevTools: Sniff feature completeness checker version ';
        include __DIR__ . '/../VERSION';
        echo PHP_EOL,
            'by Juliette Reinders Folmer', PHP_EOL, PHP_EOL;
    }

    /**
     * Display usage instructions.
     *
     * @return void
     */
    protected function showHelp()
    {
        $this->showVersion();

        echo 'Usage:', PHP_EOL,
            '    phpcs-check-feature-completeness', PHP_EOL,
            '    phpcs-check-feature-completeness [-q] [--exclude=<dir>] [directories]', PHP_EOL;

        echo PHP_EOL,
            'Options:', PHP_EOL,
            '    directories   One or more specific directories to examine.', PHP_EOL,
            '                  Defaults to the directory from which the script is run.', PHP_EOL,
            '    -q, --quiet   Turn off warnings for missing documentation.', PHP_EOL,
            '    --exclude     Comma-delimited list of (relative) directories to exclude', PHP_EOL,
            '                  from the scan.', PHP_EOL,
            '                  Defaults to excluding the /vendor/ directory.', PHP_EOL,
            '    --no-progress Disable progress in console output.', PHP_EOL,
            '    --colors      Enable colors in console output.', PHP_EOL,
            '                  (disables auto detection of color support)', PHP_EOL,
            '    --no-colors   Disable colors in console output.', PHP_EOL,
            '    -v            Verbose mode.', PHP_EOL,
            '    -h, --help    Print this help.', PHP_EOL,
            '    -V, --version Display the current version of this script.', PHP_EOL;
    }
}
