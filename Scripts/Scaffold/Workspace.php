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

final class Workspace
{
    private $path;
    private function __construct($path)
    {
        if (!is_string($path) || empty($path)) {
            throw new ScaffolderException('Invalid workspace path provided. Workspace path must be a non-empty string.');
        }

        $this->path = $path;
    }

        /**
        * @param string $path
        *
        * @return self
        *
        * @throws \PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException
        */
    public static function fromString($path)    {
        return new self($path);
    }

    /** @return non-empty-string */
    public function getPath()
    {
        return $this->path;
    }
}
