<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Factory;

use PHPCSDevTools\Scripts\Scaffold\ContainerInterface;
use PHPCSDevTools\Scripts\Scaffold\Provider\DirectoryProvider;

/**
 * Creates directory provider instances for the scaffold container.
 *
 * @implements FactoryInterface<DirectoryProvider>
 */
final class DirectoryProviderFactory implements FactoryInterface
{

    /**
     * Create a directory provider instance.
     *
     * @param ContainerInterface $container the service container
     *
     * @return DirectoryProvider
     */
    public function __invoke(ContainerInterface $container)
    {
        return new DirectoryProvider($container->get(
            'PHPCSDevTools\\Scripts\\Scaffold\\Collection\\StandardCollectionInterface'
        ));
    }
}
