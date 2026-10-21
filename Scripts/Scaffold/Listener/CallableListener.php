<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Listener;

use PHPCSDevTools\Scripts\Scaffold\Event\EventInterface;

/**
 * A listener that wraps a callable.
 */
final class CallableListener implements ListenerInterface
{

    /**
     * The callable to invoke when the listener is called.
     *
     * @var callable(EventInterface):void
     */
    private $callback;

    /**
     * Create a new CallableListener instance.
     *
     * @param callable(EventInterface):void $callback the callable to invoke when the listener is called
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * Invoke the listener with the given event.
     *
     * @param EventInterface $event the event to handle
     *
     * @return void
     */
    public function __invoke(EventInterface $event)
    {
        \call_user_func($this->callback, $event);
    }
}
