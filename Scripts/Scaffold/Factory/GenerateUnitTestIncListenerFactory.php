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
use PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationStartedEvent\GenerateUnitTestIncListener;

/**
 * Creates fixture generation listeners for the scaffold container.
 *
 * @implements FactoryInterface<GenerateUnitTestIncListener>
 */
final class GenerateUnitTestIncListenerFactory implements FactoryInterface
{

    /**
     * Create a fixture generation listener instance.
     *
     * @param ContainerInterface $container the service container
     *
     * @return GenerateUnitTestIncListener
     */
    public function __invoke(ContainerInterface $container)
    {
        return new GenerateUnitTestIncListener(
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\FilesystemInterface'),
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\Template\\TemplateRendererInterface'),
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestIncPathResolverInterface'),
            $container->get('PHPCSDevTools\\Scripts\\Utils\\Writer')
        );
    }
}
