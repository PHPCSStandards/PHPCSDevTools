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

use PHPCSDevTools\Scripts\Scaffold\Console\Application;
use PHPCSDevTools\Scripts\Scaffold\ContainerInterface;

/**
 * Creates scaffolder instances for the scaffold container.
 *
 * @implements FactoryInterface<Application>
 */
final class ApplicationFactory implements FactoryInterface
{

    /**
     * Create a scaffolder instance.
     *
     * @param ContainerInterface $container the service container
     *
     * @return Application
     */
    public function __invoke(ContainerInterface $container)
    {
        return new Application($container->get('PHPCSDevTools\\Scripts\\Scaffold\\DispatcherInterface'));
    }
}
