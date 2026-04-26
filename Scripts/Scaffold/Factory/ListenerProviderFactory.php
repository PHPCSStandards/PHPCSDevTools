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
use PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException;
use PHPCSDevTools\Scripts\Scaffold\ListenerProvider;

/**
 * Creates listener providers for the scaffold container.
 *
 * @implements FactoryInterface<ListenerProvider>
 */
final class ListenerProviderFactory implements FactoryInterface
{

    /**
     * Create a listener provider instance.
     *
     * @param ContainerInterface $container the service container
     *
     * @return ListenerProvider
     */
    public function __invoke(ContainerInterface $container)
    {
        $projectRoot = \dirname(\dirname(\dirname(__DIR__)));

        $listenersConfigPath = \implode(\DIRECTORY_SEPARATOR, [
            $projectRoot,
            'config',
            'scaffold',
            'listeners.php',
        ]);

        if (\is_file($listenersConfigPath) === false) {
            throw new ScaffolderException('Listeners configuration file not found: ' . $listenersConfigPath);
        }

        $listeners = require $listenersConfigPath;

        if (\is_array($listeners) === false) {
            throw new ScaffolderException(
                'Listeners configuration file must return an array, got: ' . \gettype($listeners)
            );
        }

        return new ListenerProvider($container, $listeners);
    }
}
