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
use PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationStartingEvent\PrintHelpMessageAndExitApplicationListener;

/**
 * Creates help message listeners for the scaffold container.
 *
 * @implements FactoryInterface<PrintHelpMessageAndExitApplicationListener>
 */
final class PrintHelpMessageAndExitApplicationListenerFactory implements FactoryInterface
{

    /**
     * Create a help message listener instance.
     *
     * @param ContainerInterface $container the service container
     *
     * @return PrintHelpMessageAndExitApplicationListener
     */
    public function __invoke(ContainerInterface $container)
    {
        return new PrintHelpMessageAndExitApplicationListener($container->get('PHPCSDevTools\\Scripts\\Utils\\Writer'));
    }
}
