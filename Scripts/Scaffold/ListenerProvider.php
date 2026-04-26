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

use PHPCSDevTools\Scripts\Scaffold\Event\EventInterface;
use PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException;
use PHPCSDevTools\Scripts\Scaffold\Listener\ListenerInterface;

final class ListenerProvider implements ListenerProviderInterface
{
    const EVENT_INTERFACE = 'PHPCSDevTools\\Scripts\\Scaffold\\Event\\EventInterface';

    const LISTENER_INTERFACE = 'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ListenerInterface';

    /**
     * The service container.
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * The mapping of event classes to their listeners.
     *
     * @var array<class-string<EventInterface>,list<class-string<ListenerInterface>>>
     */
    private $listeners = [];

    /**
     * Create a new ListenerProvider instance.
     *
     * @param ContainerInterface                                                        $container the service container
     * @param array<class-string<EventInterface>,list<class-string<ListenerInterface>>> $listeners the mapping of event classes to their listeners
     */
    public function __construct(ContainerInterface $container, array $listeners = [])
    {
        $this->container = $container;
        foreach ($listeners as $eventClass => $listenerClasses) {
            foreach ($listenerClasses as $listenerClass) {
                $this->listen($eventClass, $listenerClass);
            }
        }
    }

    /**
     * Listen for an event by adding a listener for the given event class.
     *
     * @param class-string<EventInterface>    $eventClass    the event class for which to add the listener
     * @param class-string<ListenerInterface> $listenerClass the listener class for the given event class
     *
     * @return void
     */
    public function listen($eventClass, $listenerClass)
    {
        if (
            \is_subclass_of($eventClass, self::EVENT_INTERFACE, true) === false
            && $eventClass !== self::EVENT_INTERFACE
        ) {
            throw new \InvalidArgumentException(\sprintf(
                'The event class "%s" must implement the "%s".',
                $eventClass,
                self::EVENT_INTERFACE
            ));
        }

        if (\is_subclass_of($listenerClass, self::LISTENER_INTERFACE, true) === false) {
            throw new \InvalidArgumentException(\sprintf(
                'The listener class "%s" must implement the "%s".',
                $listenerClass,
                self::LISTENER_INTERFACE
            ));
        }

        if (\array_key_exists($eventClass, $this->listeners) === false) {
            $this->listeners[$eventClass] = [];
        }

        $this->listeners[$eventClass][] = $listenerClass;
    }

    /**
     * Provide listeners for the given event.
     *
     * @param EventInterface $event the event for which to provide listeners
     *
     * @return ListenerInterface[] an array of listeners for the given event
     */
    public function provide(EventInterface $event)
    {
        $listeners = [];

        $eventClass = \get_class($event);

        foreach ($this->listeners as $registeredEventClass => $registeredListeners) {
            if (\is_a($eventClass, $registeredEventClass, true) === false) {
                continue;
            }

            foreach ($registeredListeners as $registeredListener) {
                $listener = $this->container->get($registeredListener);

                if (($listener instanceof ListenerInterface) === false) {
                    throw new ScaffolderException(\sprintf(
                        'The listener class "%s" must implement the "%s".',
                        $registeredListener,
                        self::LISTENER_INTERFACE
                    ));
                }

                $listeners[] = $listener;
            }
        }

        return $listeners;
    }
}
