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

interface DispatcherInterface
{

    /**
     * Dispatches an event to all relevant listeners.
     *
     * @param EventInterface $event the event to dispatch
     *
     * @return void
     */
    public function dispatch(EventInterface $event);
}
