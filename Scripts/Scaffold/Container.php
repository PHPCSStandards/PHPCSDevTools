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
use PHPCSDevTools\Tests\Scaffold\ContainerTest;

/**
 * Dependency injection container implementation.
 *
 * @template TAlias of object
 * @template TService of object
 * @template TFactory of FactoryInterface<TService>
 *
 * @see ContainerTest
 */
final class Container implements ContainerInterface
{
    const FACTORY_INTERFACE = 'PHPCSDevTools\\Scripts\\Scaffold\\Factory\\FactoryInterface';

    /**
     * The array of aliases registered in the container.
     *
     * @var array<class-string<TAlias>,class-string<TService>>
     */
    private $aliases = [];

    /**
     * The array of factories registered in the container.
     *
     * @var array<class-string<TService>, class-string<TFactory>>
     */
    private $factories = [];

    /**
     * The array of services registered in the container.
     *
     * @var array<class-string<TService>, TService>
     */
    private $services = [];

    /**
     * Create a new container instance.
     *
     * @param array<class-string<TAlias>, class-string<TService>>   $aliases   the aliases to register in the container
     * @param array<class-string<TService>, class-string<TFactory>> $factories the factories to register in the container
     * @param array<class-string<TService>, TService>               $services  the services to register in the container
     */
    public function __construct(array $aliases = [], array $factories = [], array $services = [])
    {
        $class = get_class($this);

        $this->aliases['PHPCSDevTools\\Scripts\\Scaffold\\ContainerInterface'] = $class;
        $this->services[$class] = $this;

        foreach ($aliases as $alias => $service) {
            $this->alias($alias, $service);
        }

        foreach ($factories as $service => $factory) {
            $this->factory($service, $factory);
        }

        foreach ($services as $service => $instance) {
            $this->set($service, $instance);
        }
    }

    /**
     * Register an alias in the container.
     *
     * @param class-string<TAlias>   $alias   the alias to register
     * @param class-string<TService> $service the name of the service the alias points to
     *
     * @return void
     */
    public function alias($alias, $service)
    {
        \assert(\is_string($alias));
        \assert(\is_string($service));

        if ($alias === $service) {
            throw new \InvalidArgumentException(\sprintf('Alias "%s" cannot point to itself.', $alias));
        }

        if (\array_key_exists($alias, $this->aliases)) {
            throw new \InvalidArgumentException(\sprintf(
                'Alias "%s" cannot be registered because an alias with the same name already exists.',
                $alias
            ));
        }

        if (\array_key_exists($service, $this->aliases) && $this->aliases[$service] === $alias) {
            throw new \InvalidArgumentException(\sprintf(
                'Alias "%s" cannot point to Service "%s" because it creates a circular reference.',
                $alias,
                $service
            ));
        }

        $this->aliases[$alias] = $service;
    }

    /**
     * Register a factory in the container.
     *
     * @param class-string<TService> $service the service to register the factory for
     * @param class-string<TFactory> $factory the factory to register
     *
     * @return void
     */
    public function factory($service, $factory)
    {
        if (\is_string($service) === false) {
            throw new \InvalidArgumentException('Service name must be a class-string.');
        }

        if (\is_string($factory) === false) {
            throw new \InvalidArgumentException('Factory class name must be a class-string.');
        }

        if (\array_key_exists($service, $this->factories)) {
            throw new \InvalidArgumentException(\sprintf(
                'Factory for service "%s" cannot be registered because a factory with the same name already exists.',
                $service
            ));
        }

        if (\is_a($factory, self::FACTORY_INTERFACE, true) === false) {
            throw new \InvalidArgumentException(\sprintf(
                'Factory for service "%s" cannot be registered because the provided factory class does not implement "%s".',
                $service,
                self::FACTORY_INTERFACE
            ));
        }

        $this->factories[$service] = $factory;
    }

    /**
     * Get a service from the container.
     *
     * @param class-string<TService> $service the name of the service to get
     *
     * @throws \InvalidArgumentException if the service is not registered in the container
     *
     * @return TService
     */
    public function get($service)
    {
        \assert(\is_string($service));

        while (\array_key_exists($service, $this->aliases)) {
            $service = $this->aliases[$service];
        }

        if (\array_key_exists($service, $this->services)) {
            return $this->services[$service];
        }

        if (\array_key_exists($service, $this->factories)) {
            $factory = $this->get($this->factories[$service]);

            \assert($factory instanceof FactoryInterface);

            $this->set($service, $factory($this));

            return $this->services[$service];
        }

        throw new \InvalidArgumentException(\sprintf('Service "%s" is not registered in the container.', $service));
    }

    /**
     * Register a service in the container.
     *
     * @param class-string<TService> $service  the name of the service to register
     * @param TService               $instance the service instance to register
     *
     * @return void
     */
    public function set($service, $instance)
    {
        if (\is_string($service) === false) {
            throw new \InvalidArgumentException('Service name must be a string.');
        }

        if (\is_object($instance) === false) {
            throw new \InvalidArgumentException(\sprintf(
                'Service "%s" cannot be registered because the provided instance is not an object.',
                $service
            ));
        }

        if (\array_key_exists($service, $this->services) === true) {
            throw new \InvalidArgumentException(\sprintf(
                'Service "%s" cannot be registered because a service with the same name already exists.',
                $service
            ));
        }

        if (\is_a($instance, $service, true) === false) {
            throw new \InvalidArgumentException(\sprintf(
                'Service "%s" cannot be registered because the provided instance is not an instance of "%s", got: %s.',
                $service,
                $service,
                \get_class($instance)
            ));
        }

        $this->services[$service] = $instance;
    }
}
