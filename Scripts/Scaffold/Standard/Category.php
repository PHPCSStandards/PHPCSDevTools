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
 * Value object for a PHPCS sniff category name.
 */
final class Category implements CategoryInterface
{

    /**
     * The category name.
     *
     * @var non-empty-string
     */
    private $category;

    /**
     * Cache of created instances, keyed by name.
     *
     * @var array<non-empty-string, self>
     */
    private static $cache = [];

    /**
     * Create a new category name value object.
     *
     * @param string $category the category name
     */
    private function __construct($category)
    {
        if (\is_string($category) === false) {
            throw new ScaffolderException('Category must be a string.');
        }

        if (\trim($category) === '') {
            throw new ScaffolderException('Category must be a non-empty string.');
        }

        $this->category = $category;
    }

    /**
     * Clean up the cache when an instance is destroyed.
     *
     * @return void
     */
    public function __destruct()
    {
        unset(self::$cache[$this->category]);
    }

    /**
     * Get the name as a string.
     *
     * @return non-empty-string
     */
    public function toString()
    {
        return $this->category;
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
