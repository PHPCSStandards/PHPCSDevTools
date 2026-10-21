<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Standard;

use PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException;

/**
 * Represents a standard directory path.
 */
final class Directory implements DirectoryInterface
{

    /**
     * The standard directory.
     *
     * @var non-empty-string
     */
    private $path;

    /**
     * Cache of created instances, keyed by name.
     *
     * @var array<non-empty-string, self>
     */
    private static $cache = [];

    /**
     * Create a standard directory value object.
     *
     * @param string $path the standard directory path
     *
     * @throws ScaffolderException
     */
    private function __construct($path)
    {
        if (\is_string($path) === false) {
            throw new ScaffolderException('Standard directory must be a string.');
        }

        if (\trim($path) === '') {
            throw new ScaffolderException('Standard directory must be a non-empty string.');
        }

        if (\is_dir($path) === false) {
            throw new ScaffolderException('Standard directory must be a valid directory path.');
        }

        $this->path = $path;
    }

    /**
     * Clean up the cache when an instance is destroyed.
     *
     * @return void
     */
    public function __destruct()
    {
        unset(self::$cache[$this->path]);
    }

    /**
     * Get the standard directory path.
     *
     * @return non-empty-string
     */
    public function toString()
    {
        return $this->path;
    }

    /**
     * Create a new value object.
     *
     * @param string $name the name
     *
     * @return self
     */
    public static function fromString($name)
    {
        if (isset(self::$cache[$name])) {
            return self::$cache[$name];
        }

        return self::$cache[$name] = new self($name);
    }
}
