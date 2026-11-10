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
use PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationStartedEvent\GenerateUnitTestIncFixedListener;

/**
 * Creates fixed-fixture generation listeners for the scaffold container.
 *
 * @implements FactoryInterface<GenerateUnitTestIncFixedListener>
 */
final class GenerateUnitTestIncFixedListenerFactory implements FactoryInterface
{

    /**
     * Create a fixed-fixture generation listener instance.
     *
     * @param ContainerInterface $container the service container
     *
     * @return GenerateUnitTestIncFixedListener
     */
    public function __invoke(ContainerInterface $container)
    {
        return new GenerateUnitTestIncFixedListener(
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\FilesystemInterface'),
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\Template\\TemplateRendererInterface'),
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestIncFixedPathResolverInterface'),
            $container->get('PHPCSDevTools\\Scripts\\Utils\\Writer')
        );
    }
}
