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
use PHPCSDevTools\Scripts\Scaffold\Event\Exception\ExceptionEventInterface;
use PHPCSDevTools\Scripts\Scaffold\Event\Exception\ListenerExceptionEvent;
use PHPCSDevTools\Scripts\Scaffold\Listener\ListenerInterface;

final class Dispatcher implements DispatcherInterface
{

    /**
     * The listener provider to use for dispatching events.
     *
     * @var ListenerProviderInterface
     */
    private $listenerProvider;

    /**
     * Create a new dispatcher.
     *
     * @param ListenerProviderInterface $listenerProvider the listener provider to use for dispatching events
     */
    public function __construct(ListenerProviderInterface $listenerProvider)
    {
        $this->listenerProvider = $listenerProvider;
    }

    /**
     * Dispatch an event to all relevant listeners.
     *
     * @param EventInterface $event the event to dispatch
     *
     * @return void
     */
    public function dispatch(EventInterface $event)
    {
        foreach ($this->listenerProvider->provide($event) as $listener) {
            \assert($listener instanceof ListenerInterface);

            try {
                $listener($event);
            } catch (\Exception $exception) {
                if ($event instanceof ExceptionEventInterface) {
                    throw $event->getException();
                }

                $this->dispatch(new ListenerExceptionEvent($event, $exception, $listener));

                throw $exception;
            }
        }
    }
}
