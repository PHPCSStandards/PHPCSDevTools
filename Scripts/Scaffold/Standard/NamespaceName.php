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
 * Represents a validated standard namespace prefix.
 */
final class NamespaceName implements NamespaceNameInterface
{

    /**
     * The standard namespace.
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
     * Create a standard namespace value object.
     *
     * @param string $name the namespace declared in the ruleset
     *
     * @throws ScaffolderException
     */
    private function __construct($name)
    {
        if (\is_string($name) === false) {
            throw new ScaffolderException('Standard namespace must be a string.');
        }

        if (\trim($name) === '') {
            throw new ScaffolderException('Standard namespace must be a non-empty string.');
        }

        if (\preg_match('/^[A-Za-z_]\w*(\\\\[A-Za-z_]\w*)*$/', $name) !== 1) {
            throw new ScaffolderException(
                'Standard namespace must be a valid PHP namespace using letters, numbers, underscores, and backslashes.'
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
     * Get the standard namespace.
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
