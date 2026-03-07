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

final class Workspace implements WorkspaceInterface
{

    /**
     * The path to the workspace.
     *
     * @var non-empty-string
     */
    private $path;

    /**
     * Create a Workspace instance from a string path.
     *
     * @param non-empty-string $path the path to the workspace
     *
     * @throws ScaffolderException
     */
    public function __construct($path)
    {
        if (\is_string($path) === false) {
            throw new ScaffolderException('Invalid workspace path provided. Workspace path must be a string.');
        }

        if (empty($path)) {
            throw new ScaffolderException(
                'Invalid workspace path provided. Workspace path must be a non-empty string.'
            );
        }

        if (\is_dir($path) === false) {
            throw new ScaffolderException(
                'Invalid workspace path provided. Workspace path must be a valid directory.'
            );
        }

        $this->path = $path;
    }

    /**
     * Get the workspace path.
     *
     * @return non-empty-string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @throws ScaffolderException
     *
     * @return self
     */
    public static function fromCurrentWorkingDirectory()
    {
        $path = \getcwd();

        if ($path === false) {
            throw new ScaffolderException('Unable to determine current working directory.');
        }

        return new self($path);
    }
}
