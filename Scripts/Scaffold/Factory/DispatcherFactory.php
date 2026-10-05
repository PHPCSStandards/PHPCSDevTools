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
use PHPCSDevTools\Scripts\Scaffold\Dispatcher;

/**
 * Creates event dispatchers for the scaffold container.
 *
 *  @implements FactoryInterface<Dispatcher>
 */
final class DispatcherFactory implements FactoryInterface
{

    /**
     * Create an event dispatcher instance.
     *
     * @param ContainerInterface $container the service container
     *
     * @return Dispatcher
     */
    public function __invoke(ContainerInterface $container)
    {
        return new Dispatcher($container->get('PHPCSDevTools\\Scripts\\Scaffold\\ListenerProviderInterface'));
    }
}
