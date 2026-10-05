<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationStartingEvent;

use PHPCSDevTools\Scripts\Scaffold\Event\ApplicationStartingEvent;
use PHPCSDevTools\Scripts\Scaffold\Event\EventInterface;
use PHPCSDevTools\Scripts\Scaffold\Listener\ListenerInterface;

/**
 * Listener for the ScaffoldStartingEvent that sets up an error handler to convert errors to exceptions.
 */
final class SetupErrorHandlerListener implements ListenerInterface
{

    /**
     * Handle the ScaffoldStartingEvent.
     *
     * @param ApplicationStartingEvent $event the event to handle
     *
     * @return void
     */
    public function __invoke(EventInterface $event)
    {
        \assert($event instanceof ApplicationStartingEvent);

        \set_error_handler(static function ($code, $message, $filename, $line) {
            throw new \ErrorException($message, $code, 0, $filename, $line);
        });
    }
}
