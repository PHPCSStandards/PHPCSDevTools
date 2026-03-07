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

interface FileReaderInterface
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
    public function read($filePath);
}
