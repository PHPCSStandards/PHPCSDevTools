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
 * Stream wrapper to simulate a directory that exists but cannot be read.
 *
 * phpcs:ignoreFile
 */
final class FilesystemGhostDirectoryStreamWrapper
{

    /**
     * @var resource
     */
    public $context;

    /**
     * @param string $path    directory path
     * @param int    $mode    directory mode
     * @param int    $options mkdir options
     *
     * @return bool
     */
    public function mkdir($path, $mode, $options)
    {
        return true;
    }

    /**
     * @param string $path  queried path
     * @param int    $flags stat flags
     *
     * @return bool
     */
    public function url_stat($path, $flags)
    {
        return false;
    }
}
