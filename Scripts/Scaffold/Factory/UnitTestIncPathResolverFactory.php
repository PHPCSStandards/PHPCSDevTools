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
use PHPCSDevTools\Scripts\Scaffold\Resolver\UnitTestIncPathResolver;

/**
 * Creates unit test fixture path resolvers for the scaffold container.
 *
 * @implements FactoryInterface<UnitTestIncPathResolver>
 */
final class UnitTestIncPathResolverFactory implements FactoryInterface
{

    /**
     * Create a unit test fixture path resolver instance.
     *
     * @param ContainerInterface $container the service container
     *
     * @return UnitTestIncPathResolver
     */
    public function __invoke(ContainerInterface $container)
    {
        return new UnitTestIncPathResolver(
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\Provider\\DirectoryProviderInterface')
        );
    }
}
