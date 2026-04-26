<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Collection;

use PHPCSDevTools\Scripts\Scaffold\Standard\NameInterface;
use PHPCSDevTools\Scripts\Scaffold\StandardInterface;

interface StandardCollectionInterface
{

    /**
     * Add the standard metadata to the collection.
     *
     * @param StandardInterface $standard The standard metadata to add
     *
     * @return void
     */
    public function add(StandardInterface $standard);

    /**
     * Get the standard metadata for a given standard name.
     *
     * @param NameInterface $standardName The name of the standard
     *
     * @return StandardInterface
     */
    public function get(NameInterface $standardName);

    /**
     * Get all discovered standards keyed by standard name.
     *
     * @return array<non-empty-string, StandardInterface>
     */
    public function toArray();
}
