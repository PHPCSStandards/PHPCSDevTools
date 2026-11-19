<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests\Scaffold;

/**
 * Stream wrapper to simulate a file that exists but is not readable.
 *
 * phpcs:ignoreFile
 */
final class FilesystemUnreadableStreamWrapper
{
    /**
     * @var resource
     */
    public $context;

    /**
     * Simulate open failure due to unreadable file.
     *
     * @param string $path
     * @param string $mode
     * @param int    $options
     * @param string $opened_path
     *
     * @return bool
     */
    public function stream_open($path, $mode, $options, &$opened_path)
    {
        // Simulate unreadable file by returning false.
        return false;
    }

    /**
     * Simulate file existence but unreadable.
     *
     * @param string $path
     * @param int    $flags
     *
     * @return array<int|string, int>
     */
    public function url_stat($path, $flags)
    {
        // mode: regular file, but not readable (0000)
        return [
            0,
            0,
            0100000,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            'mode' => 0100000,
        ];
    }
}
