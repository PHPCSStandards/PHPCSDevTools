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
 * Stream wrapper to simulate a read failure when trying to open a file.
 *
 * phpcs:ignoreFile
 */
final class FilesystemReadFailureStreamWrapper
{

    /**
     * @var resource
     */
    public $context;

    /**
     * @param string $path        path being opened
     * @param int    $mode        stat mode to report
     * @param int    $options     wrapper options
     * @param string $opened_path opened path reference
     *
     * @return bool
     */
    public function stream_open($path, $mode, $options, &$opened_path)
    {
        \trigger_error('Simulated read failure', \E_USER_WARNING);

        return false;
    }

    /**
     * @param string $path  queried path
     * @param int    $flags stat flags
     *
     * @return array<int|string, int>
     */
    public function url_stat($path, $flags)
    {
        return [
            0,
            0,
            0100444,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            'mode' => 0100444,
        ];
    }
}
