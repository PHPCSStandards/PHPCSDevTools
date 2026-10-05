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
use PHPCSDevTools\Scripts\Scaffold\Listener\DebugListener;

/**
 * Creates debug listeners for the scaffold container.
 *
 * @implements FactoryInterface<DebugListener>
 */
final class DebugListenerFactory implements FactoryInterface
{

    /**
     * Create a debug listener instance.
     *
     * @param ContainerInterface $container the service container
     *
     * @return DebugListener
     */
    public function __invoke(ContainerInterface $container)
    {
        return new DebugListener($container->get('PHPCSDevTools\\Scripts\\Utils\\Writer'));
    }
}
