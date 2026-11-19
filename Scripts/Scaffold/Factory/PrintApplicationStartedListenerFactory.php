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
use PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationStartedEvent\PrintApplicationStartedListener;

/**
 * Creates scaffolding started listeners for the scaffold container.
 *
 * @implements FactoryInterface<PrintApplicationStartedListener>
 */
final class PrintApplicationStartedListenerFactory implements FactoryInterface
{

    /**
     * Create a scaffolding started listener instance.
     *
     * @param ContainerInterface $container the service container
     *
     * @return PrintApplicationStartedListener
     */
    public function __invoke(ContainerInterface $container)
    {
        return new PrintApplicationStartedListener(
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\Provider\\DirectoryProviderInterface'),
            $container->get('PHPCSDevTools\\Scripts\\Utils\\Writer')
        );
    }
}
