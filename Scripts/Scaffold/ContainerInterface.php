<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold;

use PHPCSDevTools\Scripts\Scaffold\Factory\FactoryInterface;

interface ContainerInterface
{

    /**
     * Register an alias in the container.
     *
     * @param string $alias   the alias to register
     * @param string $service the name of the service the alias points to
     *
     * @return void
     */
    public function alias($alias, $service);

    /**
     * Register a factory in the container.
     *
     * @template T of object
     *
     * @param class-string<T>                                 $service the service to register the factory for
     * @param class-string<FactoryInterface<class-string<T>>> $factory the factory to register
     *
     * @return void
     */
    public function factory($service, $factory);

    /**
     * Get a service from the container.
     *
     * @template T of object
     *
     * @param class-string<T> $service the name of the service to get
     *
     * @throws \InvalidArgumentException if the service is not registered in the container
     *
     * @return T
     */
    public function get($service);

    /**
     * Register a service in the container.
     *
     * @param string $service  the name of the service to register
     * @param object $instance the service instance to register
     *
     * @return void
     */
    public function set($service, $instance);
}
