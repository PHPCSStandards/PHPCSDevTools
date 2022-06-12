<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Utils;

use PHPCSDevTools\Scripts\Utils\Writer;

/**
 * Utilities for writing to the command line.
 *
 * ---------------------------------------------------------------------------------------------
 * This class is not part of the public API. Backward compatibility is not guaranteed.
 * ---------------------------------------------------------------------------------------------
 *
 * @codeCoverageIgnore
 *
 * @since 2.0.0
 */
final class CliWriter implements Writer
{

    /**
     * Keep track of what output stream was last flushed.
     *
     * @var resource
     */
    private $lastFlushed;

    /**
     * Send output to STDOUT.
     *
     * @param string $text Output to send.
     *
     * @return void
     */
    public function toStdout($text)
    {
        if (isset($this->lastFlushed) && $this->lastFlushed !== STDERR) {
            $this->flush(\STDERR);
        }

        \fwrite(\STDOUT, $text);
    }

    /**
     * Send output to STDERR.
     *
     * @param string $text Output to send.
     *
     * @return void
     */
    public function toStderr($text)
    {
        if (isset($this->lastFlushed) && $this->lastFlushed !== STDOUT) {
            $this->flush(\STDOUT);
        }

        \fwrite(\STDERR, $text);
    }

    /**
     * Flush buffered output to the screen.
     *
     * Flushing regularly should prevent the output from stdout and stderr being shown in
     * a jumbled order.
     *
     * @param resource|null $stream Either STDOUT or STDERR. Not passing it will flush both.
     *
     * @return void
     */
    public function flush($stream = null)
    {
        if (isset($stream) === false) {
            if (isset($this->lastFlushed) === false) {
                \fflush(\STDERR);
                \fflush(\STDOUT);
                $this->lastFlushed = \STDOUT;
                return;
            }

            $stream = ($this->lastFlushed === \STDOUT) ? \STDERR : \STDOUT;
        }

        if (\is_resource($stream)) {
            \fflush($stream);
            $this->lastFlushed = $stream;
        }
    }

    /**
     * Flush any remaining buffered output to the screen.
     *
     * @return void
     */
    public function flushOutput()
    {
        $stream = ($this->lastFlushed === \STDOUT) ? \STDERR : \STDOUT;
        $this->flush($stream);

        $stream = ($this->lastFlushed === \STDOUT) ? \STDERR : \STDOUT;
        $this->flush($stream);
    }
}
