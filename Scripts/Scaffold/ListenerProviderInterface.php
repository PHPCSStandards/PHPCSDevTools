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
use PHPCSDevTools\Scripts\Scaffold\Listener\ListenerInterface;

interface ListenerProviderInterface
{

    /**
     * Provide listeners for the given event.
     *
     * @param EventInterface $event the event for which to provide listeners
     *
     * @return ListenerInterface[] an array of listeners for the given event
     */
    public function provide(EventInterface $event);
}
