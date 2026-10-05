<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Event;

use PHPCSDevTools\Scripts\Scaffold\Console\RequestInterface;

/**
 * Dispatched when the application is starting.
 */
final class ApplicationStartingEvent implements EventInterface
{

    /**
     * The arguments passed to the script.
     *
     * @var RequestInterface
     */
    private $request;

    /**
     * Create a new instance.
     *
     * @param RequestInterface $request the $_SERVER['argv'] array passed to the script
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * Get the arguments passed to the script.
     *
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }
}
