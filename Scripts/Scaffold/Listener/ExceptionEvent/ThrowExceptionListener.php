<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Listener\ExceptionEvent;

use PHPCSDevTools\Scripts\Scaffold\Event\EventInterface;
use PHPCSDevTools\Scripts\Scaffold\Event\Exception\ExceptionEventInterface;
use PHPCSDevTools\Scripts\Scaffold\Listener\ListenerInterface;

/**
 * Listener for the ExceptionEvent that throws the exception contained in the event.
 */
final class ThrowExceptionListener implements ListenerInterface
{

    /**
     * Handle ExceptionEventInterface event.
     *
     * @param ExceptionEventInterface $event the event to handle
     *
     * @return void
     */
    public function __invoke(EventInterface $event)
    {
        \assert($event instanceof ExceptionEventInterface);

        throw $event->getException();
    }
}
