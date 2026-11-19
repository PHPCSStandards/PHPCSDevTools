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
use PHPCSDevTools\Scripts\Scaffold\Resolver\UnitTestNamespaceResolver;

/**
 * Creates unit test namespace resolvers for the scaffold container.
 *
 * @implements FactoryInterface<UnitTestNamespaceResolver>
 */
final class UnitTestNamespaceResolverFactory implements FactoryInterface
{

    /**
     * Create a unit test namespace resolver instance.
     *
     * @param ContainerInterface $container the service container
     *
     * @return UnitTestNamespaceResolver
     */
    public function __invoke(ContainerInterface $container)
    {
        return new UnitTestNamespaceResolver(
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\Provider\\NamespaceNameProviderInterface')
        );
    }
}
