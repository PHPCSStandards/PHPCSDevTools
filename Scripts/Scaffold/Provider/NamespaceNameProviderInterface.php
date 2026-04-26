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
use PHPCSDevTools\Scripts\Scaffold\Standard\NamespaceNameInterface;

interface NamespaceNameProviderInterface extends ProviderInterface
{

    /**
     * Provide the namespace name for a given standard name.
     *
     * @param NameInterface $name The standard name to provide the namespace name for
     *
     * @return NamespaceNameInterface The namespace name for the given standard name
     */
    public function provide(NameInterface $name);
}
