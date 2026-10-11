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

/**
 * Interface for a container that can be used to register and retrieve services and factories.
 */
interface ContainerInterface
{

    /**
     * Register an alias in the container.
     *
     * @template TAlias of object
     * @template TService of object
     *
     * @param class-string<TAlias>   $alias   the alias to register
     * @param class-string<TService> $service the name of the service the alias points to
     *
     * @return void
     */
    public function alias($alias, $service);

    /**
     * Register a factory in the container.
     *
     * @template TService of object
     *
     * @param class-string<TService>                   $service the service to register the factory for
     * @param class-string<FactoryInterface<TService>> $factory the factory to register
     *
     * @return void
     */
    public function factory($service, $factory);

    /**
     * Get a service from the container.
     *
     * @template TService of object
     *
     * @param class-string<TService> $service the name of the service to get
     *
     * @return TService
     *
     * @throws \InvalidArgumentException if the service is not registered in the container
     */
    public function get($service);

    /**
     * Register a service in the container.
     *
     * @template TService of object
     *
     * @param class-string<TService> $service  the name of the service to register
     * @param TService               $instance the service instance to register
     *
     * @return void
     */
    public function set($service, $instance);
}
