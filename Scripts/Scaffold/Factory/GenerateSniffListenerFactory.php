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
use PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationStartedEvent\GenerateSniffListener;

/**
 * Creates sniff generation listeners for the scaffold container.
 *
 *  @implements FactoryInterface<GenerateSniffListener>
 */
final class GenerateSniffListenerFactory implements FactoryInterface
{

    /**
     * Create a sniff generation listener instance.
     *
     * @param ContainerInterface $container the service container
     *
     * @return GenerateSniffListener
     */
    public function __invoke(ContainerInterface $container)
    {
        return new GenerateSniffListener(
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\FilesystemInterface'),
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\SniffNamespaceResolverInterface'),
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\SniffPathResolverInterface'),
            $container->get('PHPCSDevTools\Scripts\Scaffold\Resolver\SniffShortClassResolverInterface'),
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\Template\\TemplateRendererInterface'),
            $container->get('PHPCSDevTools\\Scripts\\Utils\\Writer')
        );
    }
}
