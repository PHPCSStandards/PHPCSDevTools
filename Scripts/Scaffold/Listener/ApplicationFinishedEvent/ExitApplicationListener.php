<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationFinishedEvent;

use PHPCSDevTools\Scripts\Scaffold\Event\ApplicationFinishedEvent;
use PHPCSDevTools\Scripts\Scaffold\Event\EventInterface;
use PHPCSDevTools\Scripts\Scaffold\Listener\ListenerInterface;

/**
 * Listener for the ScaffoldFinishedEvent that exits the script with the appropriate exit code.
 */
final class ExitApplicationListener implements ListenerInterface
{

    /**
     * Handle the ScaffoldFinishedEvent.
     *
     * @param ApplicationFinishedEvent $event the event to handle
     *
     * @return void
     */
    public function __invoke(EventInterface $event)
    {
        \assert($event instanceof ApplicationFinishedEvent);

        exit($event->getExitCode());
    }
}
