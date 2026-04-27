<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Provider;

use PHPCSDevTools\Scripts\Scaffold\Standard\NameInterface;

/**
 * Provider interface.
 */
interface ProviderInterface
{

    /**
     * Provide a value for a given name.
     *
     * @param NameInterface $name The name to provide a value for
     *
     * @template T of mixed
     *
     * @return T The value for the given name
     */
    public function provide(NameInterface $name);
}
