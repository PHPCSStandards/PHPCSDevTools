<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests;

use RuntimeException;
use Yoast\PHPUnitPolyfills\TestCases\XTestCase;

/**
 * Abstract test case for checking the output of command execution.
 */
abstract class IOTestCase extends XTestCase
{

    /**
     * Helper function to execute a CLI command and retrieve the results.
     *
     * @param string      $command    The CLI command to execute.
     * @param string|null $workingDir Optional. The directory in which to execute the command.
     *                                Defaults to `null` = the working directory of the current PHP process.
     *                                Note: if the command itself already contains a "working directory" argument,
     *                                this parameter will normally not need to be passed.
     *
     * @return array Format:
     *               'exitcode' int    The exit code from the command.
     *               'stdout'   string The output send to stdout.
     *               'stderr'   string The output send to stderr.
     *
     * @throws \RuntimeException When the passed arguments do not comply.
     * @throws \RuntimeException When no resource could be obtained to execute the command.
     */
    protected function executeCliCommand($command, $workingDir = null)
    {
        if (\is_string($command) === false || $command === '') {
            throw new RuntimeException('Command must be a non-empty string.');
        }

        if (\is_null($workingDir) === false && (\is_string($workingDir) === false || $workingDir === '')) {
            throw new RuntimeException('Working directory must be a non-empty string or null.');
        }

        $descriptorspec = [
            0 => ['pipe', 'r'],  // stdin
            1 => ['pipe', 'w'],  // stdout
            2 => ['pipe', 'w'],  // stderr
        ];

        $process = \proc_open($command, $descriptorspec, $pipes, $workingDir);

        if (\is_resource($process) === false) {
            throw new RuntimeException('Could not obtain a resource with proc_open() to execute the command.');
        }

        $result = [];
        \fclose($pipes[0]);

        $result['stdout'] = \stream_get_contents($pipes[1]);
        \fclose($pipes[1]);

        $result['stderr'] = \stream_get_contents($pipes[2]);
        \fclose($pipes[2]);

        $result['exitcode'] = \proc_close($process);

        return $result;
    }
}
