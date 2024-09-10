<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests\FeatureComplete\Check;

use PHPCSDevTools\Tests\FeatureComplete\Check\CheckTestCase;

/**
 * Test colorized output.
 *
 * @covers \PHPCSDevTools\Scripts\FeatureComplete\Check::validate
 * @covers \PHPCSDevTools\Scripts\FeatureComplete\Check::isComplete
 * @covers \PHPCSDevTools\Scripts\FeatureComplete\Check::hasOrphans
 *
 * @phpcs:disable Squiz.Arrays.ArrayDeclaration.DoubleArrowNotAligned -- If needed, fix once replaced by better sniff.
 */
final class ColorTest extends CheckTestCase
{

    /**
     * Directory containing the fixtures for the tests in this class.
     *
     * @var string
     */
    const FIXTURE_DIR = './Tests/Fixtures/FeatureComplete/';

    /**
     * Verify the correct text snippets will be colorized when colors are enabled.
     *
     * @dataProvider dataColors
     *
     * @param string $fixtureDir     Relative path within the fixture directory to use for the test.
     * @param string $expectedOutput Colorized snippet of the expected output.
     * @param int    $exitCode       The expected exit code.
     * @param string $src            Whether the output is expected in "stdout" or "stderr".
     * @param string $cliExtra       Optional. Additional CLI arguments to pass.
     *
     * @return void
     */
    public function testColors($fixtureDir, $expectedOutput, $exitCode, $src = 'stdout', $cliExtra = '')
    {
        $command = 'phpcs-check-feature-completeness --colors ' . $cliExtra . ' ' . self::FIXTURE_DIR . $fixtureDir;
        $result  = $this->runValidation($command);

        if ($src === 'stdout') {
            $actual = $result['writer']->getStdout();
            $this->assertStringContainsString($expectedOutput, $actual, 'Stdout does not match expectation');
        } else {
            $actual = $result['writer']->getStderr();
            $this->assertStringContainsString($expectedOutput, $actual, 'Stderr does not match expectation');
        }

        $this->assertSame($exitCode, $result['exitCode'], 'Exit code does not match expectation');
    }

    /**
     * Data provider.
     *
     * @return array<string, array<string, string|int>>
     */
    public static function dataColors()
    {
        return [
            'feature complete - header' => [
                'fixtureDir'     => 'ValidStandards/CompleteSingleSniff',
                'expectedOutput' => "\033[34mChecking sniff completeness:\033[0m",
                'exitCode'       => 0,
                'src'            => 'stderr',
            ],
            'feature complete - warning message for missing file' => [
                'fixtureDir'     => 'MissingDocFiles/SingleSniff',
                'expectedOutput' => "\033[33mWARNING\033[0m:",
                'exitCode'       => 1,
            ],
            'feature complete - error message for missing file' => [
                'fixtureDir'     => 'MissingTestFiles/SingleSniff',
                'expectedOutput' => "\033[31mERROR\033[0m:",
                'exitCode'       => 1,
            ],
            'feature complete - success' => [
                'fixtureDir'     => 'ValidStandards/CompleteSingleSniff',
                'expectedOutput' => "\033[32mFound 1 sniff accompanied by unit tests and documentation.\033[0m",
                'exitCode'       => 0,
            ],
            'feature complete - summary: has errors and warnings' => [
                'fixtureDir'     => 'MissingTestsAndDocs/MultipleSniffs',
                'expectedOutput' => "Found \033[31m3 errors\033[0m and \033[33m2 warnings\033[0m.",
                'exitCode'       => 1,
            ],
            'feature complete - summary: has errors, no warnings' => [
                'fixtureDir'     => 'MissingTestFiles/MultipleSniffs',
                'expectedOutput' => "Found \033[31m3 errors\033[0m and 0 warnings.",
                'exitCode'       => 1,
            ],
            'feature complete - summary: no errors, has warnings' => [
                'fixtureDir'     => 'MissingDocFiles/MultipleSniffs',
                'expectedOutput' => "Found 0 errors and \033[33m2 warnings\033[0m.",
                'exitCode'       => 1,
            ],
            'feature complete - summary: quiet mode - has errors' => [
                'fixtureDir'     => 'MissingTestsAndDocs/MultipleSniffs',
                'expectedOutput' => "Found \033[31m3 errors\033[0m.",
                'exitCode'       => 1,
                'src'            => 'stdout',
                'cliExtra'       => '-q',
            ],
            'orphaned files - header' => [
                'fixtureDir'     => 'ValidStandards/CompleteSingleSniff',
                'expectedOutput' => "\033[34mChecking for orphaned files:\033[0m",
                'exitCode'       => 0,
                'src'            => 'stderr',
            ],
            'orphaned files - warning message for orphaned file' => [
                'fixtureDir'     => 'HasOrphans/SingleFile',
                'expectedOutput' => "\033[33mWARNING\033[0m:",
                'exitCode'       => 1,
            ],
            'orphaned files - success' => [
                'fixtureDir'     => 'ValidStandards/CompleteSingleSniff',
                'expectedOutput' => "\033[32mNo orphaned documentation or test files found.\033[0m",
                'exitCode'       => 0,
            ],
            'orphaned files - summary: has warnings' => [
                'fixtureDir'     => 'HasOrphans/MultipleFiles',
                'expectedOutput' => "Found \033[33m12 orphaned files\033[0m.",
                'exitCode'       => 1,
            ],
        ];
    }
}
