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

use PHPCSDevTools\Scripts\FeatureComplete\Config;
use PHPCSDevTools\Scripts\Utils\FileList;

/**
 * Check that each sniff is feature complete, i.e. has unit tests and documentation.
 *
 * ---------------------------------------------------------------------------------------------
 * This class is not part of the public API. Backward compatibility is not guaranteed.
 * ---------------------------------------------------------------------------------------------
 *
 * @since 1.0.0
 */
final class Check
{

    /**
     * Regex used to filter the files down to sniffs only.
     *
     * @var string
     */
    const FILTER_REGEX = '`%1$s%2$s.*?/Sniffs/(?!Abstract).+Sniff\.php$`Di';

    /**
     * Configuration as passed on the command line
     *
     * @var \PHPCSDevTools\Scripts\FeatureComplete\Config
     */
    protected $config;

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
     *
     * @param \PHPCSDevTools\Scripts\FeatureComplete\Config $config Configuration as passed on the
     *                                                              command line.
     */
    public function __construct(Config $config)
    {
        $this->config = $config;

        $sep = \DIRECTORY_SEPARATOR;

        // Handle excluded dirs.
        $exclude = '(?!\.git/)';
        if (empty($this->config->excludedDirs) === false) {
            $excludedDirs = \array_map(
                'preg_quote',
                $this->config->excludedDirs,
                \array_fill(0, \count($this->config->excludedDirs), '`')
            );
            $exclude      = '(?!(\.git|' . \implode('|', $excludedDirs) . ')/)';
        }

        // Prepare the regexes.
        $quotedProjectRoot = \preg_quote($this->config->projectRoot . $sep, '`');
        $allFilesRegex     = \str_replace('(?!\.git/)', $exclude, FileList::BASE_REGEX);
        $allFilesRegex     = \sprintf($allFilesRegex, $quotedProjectRoot);
        $sniffsRegex       = \sprintf(self::FILTER_REGEX, $quotedProjectRoot, $exclude);

        // Get the file lists.
        $allFiles  = [];
        $allSniffs = [];
        foreach ($this->config->targetDirs as $targetDir) {
            // Get a list of all files in the target directory.
            $allFiles[] = (new FileList($targetDir, $this->config->projectRoot, $allFilesRegex))->getList();

            // Get a list of all sniffs in the target directory.
            $allSniffs[] = (new FileList($targetDir, $this->config->projectRoot, $sniffsRegex))->getList();
        }

        $allFiles = \call_user_func_array('array_merge', $allFiles);
        \sort($allFiles, \SORT_NATURAL);
        $this->allFiles = \array_flip($allFiles);

        $allSniffs = \call_user_func_array('array_merge', $allSniffs);
        \sort($allSniffs, \SORT_NATURAL);
        $this->allSniffs = $allSniffs;
    }

    /**
     * Validate the completeness of the sniffs in the repository.
     *
     * @return int Exit code.
     */
    public function validate()
    {
        echo $this->config->getVersion();

        if ($this->config->verbose > 0) {
            echo 'Target dir(s):', \PHP_EOL,
                '- ' . \implode(\PHP_EOL . '- ', $this->config->targetDirs),
                \PHP_EOL, \PHP_EOL;
        }

        if ($this->isComplete() !== true) {
            return 1;
        }

        return 0;
    }

    /**
     * Verify if all files needed for a sniff to be considered complete are available.
     *
     * @return bool
     */
    public function isComplete()
    {
        $sniffCount = \count($this->allSniffs);
        if ($sniffCount === 0) {
            echo 'No sniffs found.', \PHP_EOL;
            return true;
        }

        $docWarning    = 'WARNING: Documentation missing for       %s';
        $testError     = 'ERROR:   Unit tests missing for          %s';
        $testCaseError = 'ERROR:   Unit test case file missing for %s';

        if ($this->config->showColored === true) {
            $docWarning    = \str_replace('WARNING', "\033[33mWARNING\033[0m", $docWarning);
            $testError     = \str_replace('ERROR', "\033[31mERROR\033[0m", $testError);
            $testCaseError = \str_replace('ERROR', "\033[31mERROR\033[0m", $testCaseError);
        }

        $notices      = [];
        $warningCount = 0;
        $errorCount   = 0;
        foreach ($this->allSniffs as $i => $file) {
            if ($this->config->checkDocs === true) {
                $docFile = \str_replace(\array_keys($this->sniffToDoc), $this->sniffToDoc, $file);
                if (isset($this->allFiles[$docFile]) === false) {
                    $notices[] = \sprintf($docWarning, $file);
                    ++$warningCount;
                }
            }

            $testFile = \str_replace(\array_keys($this->sniffToUnitTest), $this->sniffToUnitTest, $file);
            if (isset($this->allFiles[$testFile]) === false) {
                $notices[] = \sprintf($testError, $file);
                ++$errorCount;
            } else {
                $fileFound = false;
                foreach ($this->testCaseExtensions as $extension) {
                    $testCaseFile = \str_replace('.php', $extension, $testFile);
                    if (isset($this->allFiles[$testCaseFile]) === true) {
                        $fileFound = true;
                        break;
                    }
                }

                if ($fileFound === false) {
                    $notices[] = \sprintf($testCaseError, $file);
                    ++$errorCount;
                }
            }

            // Show progress.
            if ($this->config->showProgress === true) {
                echo '.';

                $current = ($i + 1);
                if (($current % 60) === 0 || $current === $sniffCount) {
                    $padding = \strlen($sniffCount);

                    $filling = '';
                    if ($current === $sniffCount) {
                        $lines = \ceil($current / 60);
                        if ($lines > 1) {
                            $filling = \str_repeat(' ', (($lines * 60) - $sniffCount));
                        }
                    }

                    echo $filling, ' ', \str_pad($current, $padding, ' ', \STR_PAD_LEFT), ' / ', $sniffCount,
                        ' (', \str_pad(\round(($current / $sniffCount) * 100), 3, ' ', \STR_PAD_LEFT), '%)', \PHP_EOL;
                }
            }
        }

        /*
         * Show feedback to the user.
         */
        if (empty($notices) === false) {
            $template = 'Found %1$s%2$d error%3$s%4$s and %5$s%6$d warning%7$s%8$s.';
            if ($this->config->checkDocs === false) {
                $template = 'Found %1$s%2$d error%3$s%4$s.';
            }

            // Show the errors and warnings.
            $summary = \sprintf(
                $template,
                ($errorCount > 0 && $this->config->showColored === true) ? "\033[31m" : '',
                $errorCount,
                ($errorCount === 1) ? '' : 's',
                ($errorCount > 0 && $this->config->showColored === true) ? "\033[0m" : '',
                ($warningCount > 0 && $this->config->showColored === true) ? "\033[33m" : '',
                $warningCount,
                ($warningCount === 1) ? '' : 's',
                ($warningCount > 0 && $this->config->showColored === true) ? "\033[0m" : ''
            );

            echo \PHP_EOL,
                \implode(\PHP_EOL, $notices), \PHP_EOL,
                \PHP_EOL,
                \str_repeat('-', 39), \PHP_EOL,
                $summary, \PHP_EOL;

            return false;
        } else {
            $feedback = "All $sniffCount sniffs are";
            if ($sniffCount === 1) {
                $feedback = "Found $sniffCount sniff";
            }

            if ($this->config->checkDocs === true) {
                $feedback .= ' accompanied by unit tests and documentation.';
            } else {
                $feedback .= ' accompanied by unit tests.';
            }

            if ($this->config->showColored === true) {
                $feedback = "\033[32m{$feedback}\033[0m";
            }

            echo \PHP_EOL, $feedback, \PHP_EOL;

            return true;
        }
    }
}
