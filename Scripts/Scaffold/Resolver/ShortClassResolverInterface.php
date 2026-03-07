<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Resolver;

use PHPCSDevTools\Scripts\Scaffold\ResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\SniffNameInterface;

interface ShortClassResolverInterface extends ResolverInterface
{

    /**
     * Resolve the short class name to be created.
     *
     * @return non-empty-string
     */
    public function resolve(SniffNameInterface $sniffName);
}
