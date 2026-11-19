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
use PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationStartedEvent\GenerateUnitTestListener;

/**
 * Creates unit test generation listeners for the scaffold container.
 *
 * @implements FactoryInterface<GenerateUnitTestListener>
 */
final class GenerateUnitTestListenerFactory implements FactoryInterface
{

    /**
     * Create a unit test generation listener instance.
     *
     * @param ContainerInterface $container the service container
     *
     * @return GenerateUnitTestListener
     */
    public function __invoke(ContainerInterface $container)
    {
        return new GenerateUnitTestListener(
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\FilesystemInterface'),
            $container->get(
                'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\SniffFullyQualifiedClassResolverInterface'
            ),
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\Template\\TemplateRendererInterface'),
            $container->get(
                'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestFullyQualifiedClassResolverInterface'
            ),
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestNamespaceResolverInterface'),
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestPathResolverInterface'),
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestShortClassResolverInterface'),
            $container->get('PHPCSDevTools\\Scripts\\Utils\\Writer')
        );
    }
}
