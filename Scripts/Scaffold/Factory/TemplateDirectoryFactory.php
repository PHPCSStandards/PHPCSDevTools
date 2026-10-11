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
use PHPCSDevTools\Scripts\Scaffold\Template\TemplateDirectory;

/**
 * Creates template renderer instances for the scaffold container.
 *
 * @implements FactoryInterface<TemplateDirectory>
 */
final class TemplateDirectoryFactory implements FactoryInterface
{

    /**
     * Create a template renderer instance.
     *
     * @param ContainerInterface $container the service container
     *
     * @return TemplateDirectory
     */
    public function __invoke(ContainerInterface $container)
    {
        $projectRoot = \dirname(\dirname(\dirname(__DIR__)));

        return TemplateDirectory::fromString($projectRoot . \DIRECTORY_SEPARATOR . 'templates');
    }
}
