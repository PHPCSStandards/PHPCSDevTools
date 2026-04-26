<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Event\Exception;

use PHPCSDevTools\Scripts\Scaffold\Event\EventInterface;
use PHPCSDevTools\Scripts\Scaffold\Listener\ListenerInterface;

/**
 * ExceptionEvent class.
 *
 * @see ExceptionEventTest
 */
final class ListenerExceptionEvent implements ListenerExceptionEventInterface
{

    /**
     * The original event that caused the exception.
     *
     * @var EventInterface
     */
    private $event;

    /**
     * The exception that was thrown while handling the original event.
     *
     * @var \Exception
     */
    private $exception;

    /**
     * The listener that threw the exception.
     *
     * @var ListenerInterface
     */
    private $listener;

    /**
     * Create a new ExceptionEvent instance.
     *
     * @param EventInterface    $event     the original event that caused the exception
     * @param \Exception        $exception the exception that was thrown while handling the original event
     * @param ListenerInterface $listener  the listener that threw the exception
     */
    public function __construct(EventInterface $event, \Exception $exception, ListenerInterface $listener)
    {
        $this->event     = $event;
        $this->exception = $exception;
        $this->listener  = $listener;
    }

    /**
     * Get the original event that caused the exception.
     *
     * @return EventInterface the original event that caused the exception
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Get the exception that was thrown while handling the original event.
     *
     * @return \Exception the exception that was thrown while handling the original event
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * Get the listener that threw the exception.
     *
     * @return ListenerInterface the listener that threw the exception
     */
    public function getListener()
    {
        return $this->listener;
    }
}
