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
use PHPCSDevTools\Tests\Scaffold\WorkspaceTest;

/**
 * Represents a workspace for scaffolding operations.
 *
 * @see WorkspaceTest
 */
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
            throw new ScaffolderException('Workspace path must be a string.');
        }

        if (\trim($path) === '') {
            throw new ScaffolderException('Workspace path must be a non-empty string.');
        }

        if (\is_dir($path) === false) {
            throw new ScaffolderException('Workspace path must be a valid directory.');
        }

        $this->path = $path;
    }

    /**
     * Get the workspace path.
     *
     * @return non-empty-string
     */
    public function toString()
    {
        return $this->path;
    }

    /**
     * Create a Workspace instance from the current working directory.
     *
     * @param non-empty-string $path the path to the workspace
     *
     * @throws ScaffolderException if the current working directory is not a valid directory
     *
     * @return self
     */
    public static function fromString($path)
    {
        return new self($path);
    }
}
