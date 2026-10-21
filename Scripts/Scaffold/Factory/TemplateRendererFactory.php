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
use PHPCSDevTools\Scripts\Scaffold\Template\TemplateRenderer;

/**
 * Creates template renderer instances for the scaffold container.
 *
 * @implements FactoryInterface<TemplateRenderer>
 */
final class TemplateRendererFactory implements FactoryInterface
{

    /**
     * Create a template renderer instance.
     *
     * @param ContainerInterface $container the service container
     *
     * @return TemplateRenderer
     */
    public function __invoke(ContainerInterface $container)
    {
        return new TemplateRenderer(
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\FilesystemInterface'),
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\Template\\TemplateDirectoryInterface')
        );
    }
}
