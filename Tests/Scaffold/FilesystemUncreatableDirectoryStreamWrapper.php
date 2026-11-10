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
 * Stream wrapper to simulate a directory that cannot be created.
 *
 * phpcs:ignoreFile
 */
final class FilesystemUncreatableDirectoryStreamWrapper
{
    /**
     * @var resource
     */
    public $context;

    /**
     * Simulate mkdir failure.
     *
     * @param string $path
     * @param int    $mode
     * @param int    $options
     *
     * @return bool
     */
    public function mkdir($path, $mode, $options)
    {
        \trigger_error('Simulated mkdir failure', \E_USER_WARNING);

        return false;
    }

    /**
     * Simulate stat for directory.
     *
     * @param string $path
     * @param int    $flags
     *
     * @return bool
     */
    public function url_stat($path, $flags)
    {
        return false;
    }
}
