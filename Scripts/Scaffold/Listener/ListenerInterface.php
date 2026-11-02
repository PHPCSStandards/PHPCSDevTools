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
 * Interface ListenerInterface.
 */
interface ListenerInterface
{

    /**
     * Invoke the listener with the given event.
     *
     * @param EventInterface $event the event to handle
     *
     * @return void
     */
    public function __invoke(EventInterface $event);
}
