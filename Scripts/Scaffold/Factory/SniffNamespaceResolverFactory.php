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
use PHPCSDevTools\Scripts\Scaffold\Resolver\SniffNamespaceResolver;

/**
 * Creates sniff namespace resolvers for the scaffold container.
 *
 * @implements FactoryInterface<SniffNamespaceResolver>
 */
final class SniffNamespaceResolverFactory implements FactoryInterface
{

    /**
     * Create a sniff namespace resolver instance.
     *
     * @param ContainerInterface $container the service container
     *
     * @return SniffNamespaceResolver
     */
    public function __invoke(ContainerInterface $container)
    {
        return new SniffNamespaceResolver(
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\Provider\\NamespaceNameProviderInterface')
        );
    }
}
