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
use PHPCSDevTools\Tests\Scaffold\SniffNameTest;

/**
 * Represents a fully qualified sniff name and its parsed parts.
 *
 * @see SniffNameTest
 */
final class Sniff implements SniffInterface
{

    /**
     * The sniff name.
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
     * Create a new sniff name value object.
     *
     * @param string $name The full name, in the format "Standard.Category.Sniff"
     *
     * @throws ScaffolderException
     */
    private function __construct($name)
    {
        if (\is_string($name) === false) {
            throw new ScaffolderException('Sniff name must be a string.');
        }

        if (\trim($name) === '') {
            throw new ScaffolderException('Sniff name must be a non-empty string.');
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
     * Get the name as a string.
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
