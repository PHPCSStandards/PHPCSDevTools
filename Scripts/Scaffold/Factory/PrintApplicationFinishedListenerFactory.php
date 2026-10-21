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
use PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationFinishedEvent\PrintApplicationFinishedListener;

/**
 * Creates scaffolding finished listeners for the scaffold container.
 *
 * @implements FactoryInterface<PrintApplicationFinishedListener>
 */
final class PrintApplicationFinishedListenerFactory implements FactoryInterface
{

    /**
     * Create a scaffolding finished listener instance.
     *
     * @param ContainerInterface $container the service container
     *
     * @return PrintApplicationFinishedListener
     */
    public function __invoke(ContainerInterface $container)
    {
        return new PrintApplicationFinishedListener($container->get('PHPCSDevTools\\Scripts\\Utils\\Writer'));
    }
}
