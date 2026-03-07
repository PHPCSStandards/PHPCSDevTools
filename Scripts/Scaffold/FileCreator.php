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

final class FileCreator implements FileCreatorInterface
{

    public function create($path, $contents)
    {
        if (\is_string($path) === false) {
            throw new ScaffolderException('Invalid path provided. Path must be a non-empty string.');
        }

        if (\is_string($contents) === false) {
            throw new ScaffolderException('Invalid contents provided. Contents must be a non-empty string.');
        }

        $directory = \dirname($path);

        if (\is_dir($directory) === false) {
            \mkdir($directory, 0755, true);

            if (\is_dir($directory) === false) {
                throw new ScaffolderException('Failed to create directory: ' . $directory);
            }
        }

        $written = \file_put_contents($path, $contents);
        if ($written === false) {
            throw new ScaffolderException('Failed to write file: ' . $path);
        }
    }

    /**
     * Check if a file exists at the given path.
     *
     * @param string $path the path to the file
     *
     * @return bool true if the file exists, false otherwise
     */
    public function exists($path)
    {
        return \file_exists($path);
    }
}
