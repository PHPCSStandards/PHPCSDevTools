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
use PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationStartingEvent\PrintApplicationNameListener;

/**
 * Creates application name listeners for the scaffold container.
 *
 * @implements FactoryInterface<PrintApplicationNameListener>
 */
final class PrintApplicationNameListenerFactory implements FactoryInterface
{

    /**
     * Create an application name listener instance.
     *
     * @param ContainerInterface $container the service container
     *
     * @return PrintApplicationNameListener
     */
    public function __invoke(ContainerInterface $container)
    {
        return new PrintApplicationNameListener($container->get('PHPCSDevTools\\Scripts\\Utils\\Writer'));
    }
}
