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
use PHPUnit\Framework\TestCase;

/**
 * Test the "show help" feature.
 *
 * @covers \PHPCSDevTools\Scripts\FeatureComplete\Config::getHelp
 */
final class GetHelpTest extends TestCase
{

    /**
     * Expected help text output.
     *
     * @var string
     */
    private $expectedOutput = '
Usage:
    phpcs-check-feature-completeness
    phpcs-check-feature-completeness [-q] [--exclude=<dir>] [directories]

Options:
    directories   One or more specific directories to examine.
                  Defaults to the directory from which the script is run.
    -q, --quiet   Turn off warnings for missing documentation.
    --exclude     Comma-delimited list of (relative) directories to exclude
                  from the scan.
                  Defaults to excluding the /vendor/ directory.
    --no-progress Disable progress in console output.
    --colors      Enable colors in console output.
                  (disables auto detection of color support)
    --no-colors   Disable colors in console output.
    -v            Verbose mode.
    -h, --help    Print this help.
    -V, --version Display the current version of this script.';

    /**
     * Verify the "show help" command generates the expected output.
     *
     * @dataProvider dataShowHelp
     *
     * @param string $command The command as received from the command line.
     *
     * @return void
     */
    public function testShowHelp($command)
    {
        $regex = '`' .  \preg_quote($this->expectedOutput, '`') . '`';
        // Make the regex ignore differences in line endings.
        $regex = \preg_replace('`[\r\n]+`', '[\r\n]+', $regex);
        $this->expectOutputRegex($regex);

        $_SERVER['argv'] = \explode(' ', $command);
        new Config();
    }

    /**
     * Data provider.
     *
     * @return array
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
}
