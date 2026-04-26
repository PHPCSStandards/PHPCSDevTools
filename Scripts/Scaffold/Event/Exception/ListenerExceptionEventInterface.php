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

interface ListenerExceptionEventInterface extends ExceptionEventInterface
{

    /**
     * Get the original event that caused the exception.
     *
     * @return EventInterface the original event that caused the exception
     */
    public function getEvent();

    /**
     * Get the listener that threw the exception.
     *
     * @return ListenerInterface the listener that threw the exception
     */
    public function getListener();
}
