<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Template;

use PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException;
use PHPCSDevTools\Tests\Scaffold\Template\TemplateDirectoryTest;

/**
 * Represents the directory containing scaffold templates.
 *
 * @see TemplateDirectoryTest
 */
final class TemplateDirectory implements TemplateDirectoryInterface
{

    /**
     * The path to the template directory.
     *
     * @var non-empty-string
     */
    private $path;

    /**
     * Cache of created instances, keyed by path.
     *
     * @var array<non-empty-string, self>
     */
    private static $cache = [];

    /**
     * Create a new template directory instance.
     *
     * @param non-empty-string $path the path to the template directory
     *
     * @throws ScaffolderException if the path is invalid or does not exist
     */
    private function __construct($path)
    {
        if (\is_string($path) === false) {
            throw new ScaffolderException('Template directory path must be a string, got: ' . \gettype($path));
        }

        if (\trim($path) === '') {
            throw new ScaffolderException('Template directory path must be a non-empty string.');
        }

        if (\file_exists($path) === false) {
            throw new ScaffolderException('Template directory does not exist: ' . $path);
        }

        if (\is_dir($path) === false) {
            throw new ScaffolderException('Template directory path is not a directory: ' . $path);
        }

        $this->path = $path;
    }

    public function __destruct()
    {
        unset(self::$cache[$this->path]);
    }

    /**
     * Get the directory where scaffold templates are stored.
     *
     * @return non-empty-string
     */
    public function toString()
    {
        return $this->path;
    }

    /**
     * Create a new template directory instance from the provided path.
     *
     * @param non-empty-string $path the path to the template directory
     *
     * @throws ScaffolderException
     *
     * @return TemplateDirectoryInterface
     */
    public static function fromString($path)
    {
        if (isset(self::$cache[$path]) === true) {
            return self::$cache[$path];
        }

        return self::$cache[$path] = new self($path);
    }
}
