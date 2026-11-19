<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Factory;

use PHPCSDevTools\Scripts\Scaffold\Console\Request;
use PHPCSDevTools\Scripts\Scaffold\ContainerInterface;
use PHPCSDevTools\Tests\Scaffold\RequestFactoryTest;

/**
 * Creates application arguments for the scaffold container.
 *
 * @see RequestFactoryTest
 *
 * @implements FactoryInterface<Request>
 */
final class RequestFactory implements FactoryInterface
{

    /**
     * Create an application arguments instance.
     *
     * @param ContainerInterface $container the service container
     *
     * @return Request
     */
    public function __invoke(ContainerInterface $container)
    {
        return new Request(isset($_SERVER['argv']) === true ? $_SERVER['argv'] : []);
    }
}
