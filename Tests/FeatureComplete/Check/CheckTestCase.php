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

use PHPCSDevTools\Scripts\FeatureComplete\Config;
use PHPCSDevTools\Scripts\FeatureComplete\Check;
use PHPCSDevTools\Tests\TestWriter;
use Yoast\PHPUnitPolyfills\TestCases\XTestCase;

/**
 * Abstract test case for integration testing the Check class.
 */
abstract class CheckTestCase extends XTestCase
{

    /**
     * Assert that the resulting output from a command to stdout matches a regular expression.
     *
     * @param string $command          The command including arguments.
     * @param string $stdoutRegex      The regex against which the output should validate.
     * @param int    $expectedExitcode The expected exit code.
     *
     * @return void
     */
    protected function assertStdoutMatches($command, $stdoutRegex, $expectedExitcode)
    {
        // Make the regex ignore differences in line endings.
        $stdoutRegex = $this->regexIgnoreEol($stdoutRegex);
        $result      = $this->runValidation($command);

        $this->assertMatchesRegularExpression(
            $stdoutRegex,
            $result['writer']->getStdout(),
            'Stdout does not match expectation'
        );
        $this->assertSame($expectedExitcode, $result['exitCode'], 'Exit code does not match expectation');
    }

    /**
     * Assert that the resulting output from a command to stderr matches a regular expression.
     *
     * @param string $command          The command including arguments.
     * @param string $stderrRegex      The regex against which the output should validate.
     * @param int    $expectedExitcode The expected exit code.
     *
     * @return void
     */
    protected function assertStderrMatches($command, $stderrRegex, $expectedExitcode)
    {
        // Make the regex ignore differences in line endings.
        $stderrRegex = $this->regexIgnoreEol($stderrRegex);
        $result      = $this->runValidation($command);

        $this->assertMatchesRegularExpression(
            $stderrRegex,
            $result['writer']->getStderr(),
            'Stderr does not match expectation'
        );
        $this->assertSame($expectedExitcode, $result['exitCode'], 'Exit code does not match expectation');
    }

    /**
     * Assert that the resulting output from a command matches a regular expression.
     *
     * @param string $command          The command including arguments.
     * @param string $outputRegex      The regex against which the output should validate.
     * @param int    $expectedExitcode The expected exit code.
     *
     * @return void
     */
    protected function assertOutputMatches($command, $outputRegex, $expectedExitcode)
    {
        // Make the regex ignore differences in line endings.
        $outputRegex = $this->regexIgnoreEol($outputRegex);
        $result      = $this->runValidation($command);

        $this->assertMatchesRegularExpression(
            $outputRegex,
            $result['writer']->getOutput(),
            'Output does not match expectation'
        );
        $this->assertSame($expectedExitcode, $result['exitCode'], 'Exit code does not match expectation');
    }

    /**
     * Assert that the resulting output from a command to stdout and stderr matches regular expressions.
     *
     * @param string $command          The command including arguments.
     * @param string $stdoutRegex      The regex against which the stdout output should validate.
     * @param string $stderrRegex      The regex against which the stderr output should validate.
     * @param int    $expectedExitcode The expected exit code.
     *
     * @return void
     */
    protected function assertStdoutStdErrMatches($command, $stdoutRegex, $stderrRegex, $expectedExitcode)
    {
        // Make the regex ignore differences in line endings.
        $stdoutRegex = $this->regexIgnoreEol($stdoutRegex);
        $stderrRegex = $this->regexIgnoreEol($stderrRegex);
        $result      = $this->runValidation($command);

        $this->assertMatchesRegularExpression(
            $stdoutRegex,
            $result['writer']->getStdout(),
            'Stdout does not match expectation'
        );
        $this->assertMatchesRegularExpression(
            $stderrRegex,
            $result['writer']->getStderr(),
            'Stderr does not match expectation'
        );
        $this->assertSame($expectedExitcode, $result['exitCode'], 'Exit code does not match expectation');
    }

    /**
     * Run the actual test.
     *
     * @param string $command The command including arguments.
     *
     * @return void
     */
    protected function runValidation($command)
    {
        $_SERVER['argv'] = \explode(' ', $command);
        $writer          = new TestWriter();
        $config          = new Config($writer);
        $check           = new Check($config, $writer);
        $exitCode        = $check->validate();

        return [
            'writer'   => $writer,
            'exitCode' => $exitCode,
        ];
    }

    /**
     * Convert a regex containing hard-coded line endings to a regex which matches line endings
     * independently of operating system.
     *
     * @param string $regex Regular expression.
     *
     * @return string
     */
    protected function regexIgnoreEol($regex)
    {
        return \preg_replace('`[\r\n]+`', '[\r\n]+', $regex);
    }

    /**
     * Normalize line endings in an arbitrary text string to *nix line endings.
     *
     * @param string $text Arbitrary text string.
     *
     * @return string
     */
    protected function stringIgnoreEol($text)
    {
        return \str_replace(["\r\n", "\r"], "\n", $text);
    }
}
