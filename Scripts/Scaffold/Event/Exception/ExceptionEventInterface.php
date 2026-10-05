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

/**
 * Interface for events representing exceptions thrown while handling an event.
 */
interface ExceptionEventInterface extends EventInterface
{

    /**
     * Get the exception that was thrown while handling the original event.
     *
     * @return \Exception the exception that was thrown while handling the original event
     */
    public function getException();
}
