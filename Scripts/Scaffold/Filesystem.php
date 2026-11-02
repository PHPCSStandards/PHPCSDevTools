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

use PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException;
use PHPCSDevTools\Tests\Scaffold\FilesystemTest;

/**
 * Provides filesystem operations for scaffold generation.
 *
 * @see FilesystemTest
 */
final class Filesystem implements FilesystemInterface
{

    /**
     * Create a directory if it does not already exist.
     *
     * @param string $path directory path
     *
     * @throws ScaffolderException if the path is invalid or the directory cannot be created
     *
     * @return void
     */
    public function createDirectory($path)
    {
        if (\is_string($path) === false) {
            throw new ScaffolderException('Directory path must be a string.');
        }

        if (\trim($path) === '') {
            throw new ScaffolderException('Directory path must be a non-empty string.');
        }

        if (\is_dir($path)) {
            throw new ScaffolderException('Directory already exists: ' . $path);
        }

        try {
            \set_error_handler(static function ($code, $message, $filename, $line) {
                throw new \ErrorException($message, $code, 0, $filename, $line);
            });

            \mkdir($path, 0755, true);

            \restore_error_handler();
        } catch (\Exception $exception) {
            \restore_error_handler();

            throw new ScaffolderException(\sprintf('Failed to create directory: "%s".', $path), 0, $exception);
        }

        if (\is_dir($path) === false) {
            throw new ScaffolderException('Failed to create directory: ' . $path);
        }
    }

    /**
     * Get the current working directory.
     *
     * @return non-empty-string
     */
    public function currentWorkingDirectory()
    {
        $result = \getcwd();

        if ($result === false) {
            throw new ScaffolderException('Failed to get current working directory.');
        }

        return $result;
    }

    /**
     * Check if a file exists at the given path.
     *
     * @param string $path the path to check for existence
     *
     * @throws ScaffolderException if the path is not a valid non-empty string
     *
     * @return bool true if the file exists, false otherwise
     */
    public function exists($path)
    {
        if (\is_string($path) === false) {
            throw new ScaffolderException('Path must be a string.');
        }

        if (\trim($path) === '') {
            throw new ScaffolderException('Path must be a non-empty string.');
        }

        return \file_exists($path);
    }

    /**
     * Find all paths in a directory and its subdirectories that match the given regular expression.
     *
     * @param string $path  file or directory path
     * @param string $regex regular expression to filter results
     *
     * @return list<non-empty-string>
     */
    public function find($path, $regex)
    {
        $iterator = new \RegexIterator(
            new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator(
                    $path,
                    \FilesystemIterator::FOLLOW_SYMLINKS | \FilesystemIterator::SKIP_DOTS
                )
            ),
            $regex
        );

        $paths = [];

        foreach ($iterator as $file) {
            $paths[$file->getPathname()] = true;
        }

        return \array_keys($paths);
    }

    /**
     * Read the contents of a file.
     *
     * @param string $path the path to the file to read
     *
     * @throws ScaffolderException
     *
     * @return string
     */
    public function read($path)
    {
        if (\is_string($path) === false) {
            throw new ScaffolderException('File path must be a string.');
        }

        if (\trim($path) === '') {
            throw new ScaffolderException('File path must be a non-empty string.');
        }

        if (\file_exists($path) === false) {
            throw new ScaffolderException(\sprintf('File path "%s" does not exist.', $path));
        }

        if (\is_file($path) === false) {
            throw new ScaffolderException(\sprintf('File path "%s" is not a file.', $path));
        }

        if (\is_readable($path) === false) {
            throw new ScaffolderException(\sprintf('File path "%s" is not readable.', $path));
        }

        try {
            \set_error_handler(static function ($code, $message, $filename, $line) {
                throw new \ErrorException($message, $code, 0, $filename, $line);
            });

            $contents = \file_get_contents($path);

            \restore_error_handler();

            return $contents;
        } catch (\Exception $exception) {
            \restore_error_handler();

            throw new ScaffolderException(\sprintf('Failed to read file path "%s".', $path), 0, $exception);
        }

        //        if ($contents === false) {
        //            throw new ScaffolderException(\sprintf('Failed to read file "%s".', $path));
        //        }
        //
        //        return $contents;
    }

    /**
     * Write contents to a file at the specified path. If the file already exists, it will be overwritten.
     *
     * @param string $path     the path where the file should be created
     * @param string $contents the contents to write to the file
     *
     * @throws ScaffolderException if the path or contents are not valid
     * @throws ScaffolderException if the file cannot be created or written to
     *
     * @return void
     */
    public function write($path, $contents)
    {
        if (\is_string($path) === false) {
            throw new ScaffolderException('Path must be a string.');
        }

        if (\trim($path) === '') {
            throw new ScaffolderException('Path must be a non-empty string.');
        }

        if (\is_string($contents) === false) {
            throw new ScaffolderException('Contents must be a string.');
        }

        $directory = \dirname($path);

        if (\is_dir($directory) === false) {
            $this->createDirectory($directory);
        }

        \set_error_handler(static function ($code, $message, $filename, $line) {
            throw new \ErrorException($message, $code, 0, $filename, $line);
        });

        try {
            \file_put_contents($path, $contents);

            \restore_error_handler();
        } catch (\Exception $exception) {
            \restore_error_handler();

            throw new ScaffolderException(\sprintf('Failed to write file path "%s".', $path), 0, $exception);
        }
    }
}
