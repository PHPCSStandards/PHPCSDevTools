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
 * Value object for a PHPCS coding standard name.
 */
final class Name implements NameInterface
{

    /**
     * The standard name.
     *
     * @var non-empty-string
     */
    private $name;

    /**
     * Cache of created instances, keyed by name.
     *
     * @var array<non-empty-string, self>
     */
    private static $cache = [];

    /**
     * Create a standard name value object.
     *
     * @param string $name the standard name
     *
     * @throws ScaffolderException
     */
    private function __construct($name)
    {
        if (\is_string($name) === false) {
            throw new ScaffolderException('The standard name must be a string.');
        }

        if (\trim($name) === '') {
            throw new ScaffolderException('The standard name must be a non-empty string.');
        }

        if (\strtolower(\trim($name)) === 'internal') {
            throw new ScaffolderException('The standard name cannot be named "Internal".');
        }

        if (\preg_match('#[ .\/]#u', $name) === 1) {
            throw new ScaffolderException('The standard name cannot contain spaces, . characters or slashes.');
        }

        if (\preg_match('/^[A-Za-z_]\w*$/', $name) !== 1) {
            throw new ScaffolderException(
                'The standard name must be valid for use in a PHP namespace and may only contain letters, numbers, and underscores.'
            );
        }

        $this->name = $name;
    }

    /**
     * Clean up the cache when an instance is destroyed.
     *
     * @return void
     */
    public function __destruct()
    {
        unset(self::$cache[$this->name]);
    }

    /**
     * Get the standard name.
     *
     * @return non-empty-string
     */
    public function toString()
    {
        return $this->name;
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
