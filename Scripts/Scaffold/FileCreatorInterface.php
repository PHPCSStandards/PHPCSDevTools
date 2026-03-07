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

interface FileCreatorInterface
{

    /**
     * Creates a file at the specified path with the given contents.
     *
     * @param string $path     the path where the file should be created
     * @param string $contents the contents to write to the file
     *
     * @throws ScaffolderException if the file cannot be created or written to
     *
     * @return void
     */
    public function create($path, $contents);

    /**
     * Checks if a file exists at the specified path.
     *
     * @param string $path the path to check for file existence
     *
     * @return bool true if the file exists, false otherwise
     */
    public function exists($path);
}
