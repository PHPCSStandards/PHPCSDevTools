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

/**
 * Text writer interface
 *
 * ---------------------------------------------------------------------------------------------
 * This interface is not part of the public API. Backward compatibility is not guaranteed.
 * ---------------------------------------------------------------------------------------------
 *
 * @since 2.0.0
 */
interface Writer
{

    /**
     * Send output to STDOUT.
     *
     * @param string $text Output to send.
     *
     * @return void
     */
    public function toStdout($text);

    /**
     * Send output to STDERR.
     *
     * @param string $text Output to send.
     *
     * @return void
     */
    public function toStderr($text);
}
