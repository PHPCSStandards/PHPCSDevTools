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

final class FileReader
{
    /**
     * @throws \PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException
     */
    public function read($filePath)
    {
        if (!is_string($filePath) || empty($filePath)) {
            throw new ScaffolderException('Invalid file path provided. File path must be a non-empty string.');
        }

        if (!file_exists($filePath)) {
            throw new ScaffolderException(sprintf('File "%s" does not exist.', $filePath));
        }

        $contents = file_get_contents($filePath);

        if ($contents === false) {
            throw new ScaffolderException(sprintf('Failed to read file "%s".', $filePath));
        }

        return $contents;
    }
}
