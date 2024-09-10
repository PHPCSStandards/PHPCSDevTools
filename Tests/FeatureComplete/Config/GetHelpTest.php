<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests\FeatureComplete\Config;

use PHPCSDevTools\Scripts\FeatureComplete\Config;
use PHPCSDevTools\Tests\TestWriter;
use ReflectionMethod;
use Yoast\PHPUnitPolyfills\TestCases\XTestCase;

/**
 * Test the "show help" feature.
 *
 * @covers \PHPCSDevTools\Scripts\FeatureComplete\Config::getHelp
 */
final class GetHelpTest extends XTestCase
{

    /**
     * Expected help text output.
     *
     * @var string
     */
    private $expectedOutputNoColors = 'Usage:
  phpcs-check-feature-completeness
  phpcs-check-feature-completeness [-q] [--exclude=<dir>] [directories]

Options:
  directories <dir>     One or more specific directories to examine.
                        Defaults to the directory from which the script is run.
  -q, --quiet           Turn off warnings for missing documentation and orphaned
                        files.
                        Equivalent to running with "--no-docs --no-orphans".
  --exclude=<dir1,dir2> Comma-delimited list of (relative) directories to
                        exclude from the scan.
                        Defaults to excluding the /vendor/ directory.
  --no-docs             Disable missing documentation check.
  --no-orphans          Disable orphaned files check.
  --no-progress         Disable progress in console output.
  --colors              Enable colors in console output.
                        (disables auto detection of color support).
  --no-colors           Disable colors in console output.
  -v                    Verbose mode.
  -h, --help            Print this help.
  -V, --version         Display the current version of this script.';

    /**
     * Expected help text output with colors.
     *
     * @var string
     */
    private $expectedOutputColorized = "\033[33mUsage:\033[0m
  phpcs-check-feature-completeness
  phpcs-check-feature-completeness \033[36m[-q]\033[0m \033[36m[--exclude=<dir>]\033[0m \033[36m[directories]\033[0m

\033[33mOptions:\033[0m
  \033[32mdirectories \033[0m\033[36m<dir>    \033[0m One or more specific directories to examine.
                        Defaults to the directory from which the script is run.
  \033[32m-q, --quiet          \033[0m Turn off warnings for missing documentation and orphaned
                        files.
                        Equivalent to running with \"--no-docs --no-orphans\".
  \033[32m--exclude=\033[0m\033[36m<dir1,dir2>\033[0m Comma-delimited list of (relative) directories to
                        exclude from the scan.
                        Defaults to excluding the /vendor/ directory.
  \033[32m--no-docs            \033[0m Disable missing documentation check.
  \033[32m--no-orphans         \033[0m Disable orphaned files check.
  \033[32m--no-progress        \033[0m Disable progress in console output.
  \033[32m--colors             \033[0m Enable colors in console output.
                        (disables auto detection of color support).
  \033[32m--no-colors          \033[0m Disable colors in console output.
  \033[32m-v                   \033[0m Verbose mode.
  \033[32m-h, --help           \033[0m Print this help.
  \033[32m-V, --version        \033[0m Display the current version of this script.

";

    /**
     * Verify the "show help" command generates the expected output.
     *
     * @dataProvider dataShowHelp
     *
     * @param string $command The command as received from the command line.
     *
     * @return void
     */
    public function testShowHelpNoColors($command)
    {
        $_SERVER['argv'] = \explode(' ', $command . ' --no-colors');
        $writer          = new TestWriter();
        $config          = new Config($writer);

        $actual = $writer->getStdout();
        $actual = \str_replace(["\r\n", "\r"], "\n", $actual);

        $this->assertStringContainsString($this->expectedOutputNoColors, $actual);
    }

    /**
     * Data provider.
     *
     * @return array<string, array<string, string>>
     */
    public function dataShowHelp()
    {
        return [
            '-h'     => [
                'command' => 'phpcs-check-feature-completeness -h',
            ],
            '--help' => [
                'command' => './phpcs-check-feature-completeness --help',
            ],
        ];
    }

    /**
     * Verify the help text will be colorized correctly when colored output is enabled.
     *
     * @return void
     */
    public function testGetHelpWithColors()
    {
        $_SERVER['argv'] = \explode(' ', 'command --colors');
        $config          = new Config(new TestWriter());

        $getHelp = new ReflectionMethod($config, 'getHelp');
        $getHelp->setAccessible(true);

        $actual = $getHelp->invoke($config);
        $actual = \str_replace(["\r\n", "\r"], "\n", $actual);

        // Reset to prevent influencing other tests, even if this test would fail.
        $getHelp->setAccessible(false);

        $this->assertSame($this->expectedOutputColorized, $actual);
    }
}
