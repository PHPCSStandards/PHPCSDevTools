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
use PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationStartedEvent\GenerateDocsListener;

/**
 * Creates documentation generation listeners for the scaffold container.
 *
 * @implements FactoryInterface<GenerateDocsListener>
 */
final class GenerateDocsListenerFactory implements FactoryInterface
{

    /**
     * Create a documentation generation listener instance.
     *
     * @param ContainerInterface $container the service container
     *
     * @return GenerateDocsListener
     */
    public function __invoke(ContainerInterface $container)
    {
        return new GenerateDocsListener(
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\DocsPathResolverInterface'),
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\FilesystemInterface'),
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\Provider\\NamespaceNameProviderInterface'),
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\Template\\TemplateRendererInterface'),
            $container->get('PHPCSDevTools\\Scripts\\Utils\\Writer')
        );
    }
}
