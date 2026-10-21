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

use PHPCSDevTools\Scripts\Scaffold\ContainerInterface;

/**
 * FactoryInterface is a generic interface for factories that create instances of a specific class.
 *
 * @template T of object
 */
interface FactoryInterface
{

    /**
     * Create an instance of the class.
     *
     * @param ContainerInterface $container the service container
     *
     * @return T
     */
    public function __invoke(ContainerInterface $container);
}
