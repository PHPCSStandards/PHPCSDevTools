<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Provider;

use PHPCSDevTools\Scripts\Scaffold\Collection\StandardCollectionInterface;
use PHPCSDevTools\Scripts\Scaffold\Standard\DirectoryInterface;
use PHPCSDevTools\Scripts\Scaffold\Standard\NameInterface;

/**
 * Provides directories for standard names.
 */
final class DirectoryProvider implements DirectoryProviderInterface
{

    /**
     * The standard collection.
     *
     * @var StandardCollectionInterface
     */
    private $standardCollection;

    /**
     * Create a new DirectoryProvider instance.
     *
     * @param StandardCollectionInterface $standardCollection The standard collection
     */
    public function __construct(StandardCollectionInterface $standardCollection)
    {
        $this->standardCollection = $standardCollection;
    }

    /**
     * Provide the directory for a given standard name.
     *
     * @param NameInterface $name The standard name to provide the directory for
     *
     * @return DirectoryInterface The directory for the given standard name
     */
    public function provide(NameInterface $name)
    {
        return $this->standardCollection->get($name)->getDirectory();
    }
}
