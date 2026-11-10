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
use PHPCSDevTools\Scripts\Scaffold\Listener\ListenerExceptionEvent\PrintExceptionListener;

/**
 * Creates exception listeners for the scaffold container.
 *
 * @implements FactoryInterface<PrintExceptionListener>
 */
final class PrintExceptionListenerFactory implements FactoryInterface
{

    /**
     * Create an exception listener instance.
     *
     * @param ContainerInterface $container the service container
     *
     * @return PrintExceptionListener
     */
    public function __invoke(ContainerInterface $container)
    {
        return new PrintExceptionListener($container->get('PHPCSDevTools\\Scripts\\Utils\\Writer'));
    }
}
