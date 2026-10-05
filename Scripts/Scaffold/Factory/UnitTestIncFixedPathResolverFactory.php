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
use PHPCSDevTools\Scripts\Scaffold\Resolver\UnitTestIncFixedPathResolver;

/**
 * Creates unit test fixed fixture path resolvers for the scaffold container.
 *
 * @implements FactoryInterface<UnitTestIncFixedPathResolver>
 */
final class UnitTestIncFixedPathResolverFactory implements FactoryInterface
{

    /**
     * Create a unit test fixed fixture path resolver instance.
     *
     * @param ContainerInterface $container the service container
     *
     * @return UnitTestIncFixedPathResolver
     */
    public function __invoke(ContainerInterface $container)
    {
        return new UnitTestIncFixedPathResolver(
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\Provider\\DirectoryProviderInterface')
        );
    }
}
