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
use PHPCSDevTools\Scripts\Scaffold\Standard\NameInterface;
use PHPCSDevTools\Scripts\Scaffold\Standard\NamespaceNameInterface;

final class NamespaceNameProvider implements NamespaceNameProviderInterface
{

    /**
     * The standard collection.
     *
     * @var StandardCollectionInterface
     */
    private $standardCollection;

    /**
     * Create a new NamespaceNameProvider instance.
     *
     * @param StandardCollectionInterface $standardCollection The standard collection
     */
    public function __construct(StandardCollectionInterface $standardCollection)
    {
        $this->standardCollection = $standardCollection;
    }

    /**
     * Provide the namespace name for a given standard name.
     *
     * @param NameInterface $name The standard name to provide the namespace name for
     *
     * @return NamespaceNameInterface The namespace name for the given standard name
     */
    public function provide(NameInterface $name)
    {
        return $this->standardCollection->get($name)->getNamespaceName();
    }
}
