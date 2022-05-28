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

use PHPCSDevTools\Scripts\Utils\Writer;

/**
 * Test Helper to catch text normally send to the command line.
 *
 * @since 2.0.0
 */
final class TestWriter implements Writer
{

    /**
     * Text written to STDOUT during the test.
     *
     * @var string
     */
    private $stdout = '';

    /**
     * Text written to STDERR during the test.
     *
     * @var string
     */
    private $stderr = '';

    /**
     * All output as written during the test.
     *
     * @var string
     */
    private $output = '';

    /**
     * Catch output to STDOUT.
     *
     * @param string $text Output to send.
     *
     * @return void
     */
    public function toStdout($text)
    {
        $this->stdout .= $text;
        $this->output .= $text;
    }

    /**
     * Catch output to STDERR.
     *
     * @param string $text Output to send.
     *
     * @return void
     */
    public function toStderr($text)
    {
        $this->stderr .= $text;
        $this->output .= $text;
    }

    /**
     * Retrieve output send to STDOUT.
     *
     * @return string
     */
    public function getStdout()
    {
        return $this->stdout;
    }

    /**
     * Retrieve output send to STDERR.
     *
     * @return string
     */
    public function getStderr()
    {
        return $this->stderr;
    }

    /**
     * Retrieve all output send.
     *
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }
}
