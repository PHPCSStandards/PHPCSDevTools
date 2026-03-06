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
use PHPCSDevTools\Scripts\Utils\Writer;

final class FileCreator
{
    public function create($path, $contents)
    {
        if (! is_string($path)) {
            throw new ScaffolderException('Invalid path provided. Path must be a non-empty string.');
        }

        if (! is_string($contents)) {
            throw new ScaffolderException('Invalid contents provided. Contents must be a non-empty string.');
        }

        $directory = dirname($path);

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);

            if (! is_dir($directory)) {
                throw new ScaffolderException('Failed to create directory: ' . $directory);
            }
        }

        $written = file_put_contents($path, $contents);
        if ($written === false) {
            throw new ScaffolderException('Failed to write file: ' . $path);
        }
    }
}
