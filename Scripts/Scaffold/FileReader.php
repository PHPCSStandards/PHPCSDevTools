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

final class FileReader implements FileReaderInterface
{

    /**
     * Read the contents of a file.
     *
     * @param non-empty-string $filePath the path to the file to read
     *
     * @throws ScaffolderException
     *
     * @return non-empty-string
     */
    public function read($filePath)
    {
        if (\is_string($filePath) === false) {
            throw new ScaffolderException('Invalid file path provided. File path must be a non-empty string.');
        }

        if (empty($filePath)) {
            throw new ScaffolderException('Invalid file path provided. File path must be a non-empty string.');
        }

        if (\file_exists($filePath) === false) {
            throw new ScaffolderException(\sprintf('File "%s" does not exist.', $filePath));
        }

        $contents = \file_get_contents($filePath);

        if ($contents === false) {
            throw new ScaffolderException(\sprintf('Failed to read file "%s".', $filePath));
        }

        return $contents;
    }
}
