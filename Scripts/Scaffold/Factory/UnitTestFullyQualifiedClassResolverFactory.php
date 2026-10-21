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
use PHPCSDevTools\Scripts\Scaffold\Resolver\UnitTestFullyQualifiedClassResolver;

/**
 * Creates unit test fully qualified class resolvers for the scaffold container.
 *
 * @implements FactoryInterface<UnitTestFullyQualifiedClassResolver>
 */
final class UnitTestFullyQualifiedClassResolverFactory implements FactoryInterface
{

    /**
     * Create a unit test fully qualified class resolver instance.
     *
     * @param ContainerInterface $container the service container
     *
     * @return UnitTestFullyQualifiedClassResolver
     */
    public function __invoke(ContainerInterface $container)
    {
        return new UnitTestFullyQualifiedClassResolver(
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\Provider\\NamespaceNameProviderInterface')
        );
    }
}
