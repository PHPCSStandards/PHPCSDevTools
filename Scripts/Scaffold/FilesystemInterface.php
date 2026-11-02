<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold;

/**
 * Defines filesystem operations for scaffold classes.
 */
interface FilesystemInterface
{

    /**
     * Create a directory if it does not already exist.
     *
     * @param string $path directory path
     *
     * @return void
     */
    public function createDirectory($path);

    /**
     * Get the current working directory.
     *
     * @return non-empty-string
     */
    public function currentWorkingDirectory();

    /**
     * Check if a file or directory exists at the given path.
     *
     * @param string $path file or directory path
     *
     * @return bool
     */
    public function exists($path);

    /**
     * Find all paths in a directory and its subdirectories that match the given regular expression.
     *
     * @param string $path  file or directory path
     * @param string $regex regular expression to filter results
     *
     * @return list<non-empty-string>
     */
    public function find($path, $regex);

    /**
     * Read the contents of a file.
     *
     * @param string $filePath file path
     *
     * @return string
     */
    public function read($filePath);

    /**
     * Write contents to a file.
     *
     * @param string $path     file path
     * @param string $contents file contents
     *
     * @return void
     */
    public function write($path, $contents);
}
