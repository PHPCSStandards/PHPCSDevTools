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
use PHPCSDevTools\Scripts\Scaffold\Resolver\SniffPathResolver;

/**
 * Creates sniff path resolvers for the scaffold container.
 *
 * @implements FactoryInterface<SniffPathResolver>
 */
final class SniffPathResolverFactory implements FactoryInterface
{

    /**
     * Create a sniff path resolver instance.
     *
     * @param ContainerInterface $container the service container
     *
     * @return SniffPathResolver
     */
    public function __invoke(ContainerInterface $container)
    {
        return new SniffPathResolver(
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\Provider\\DirectoryProviderInterface')
        );
    }
}
