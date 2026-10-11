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

use PHPCSDevTools\Scripts\Scaffold\DotSeparatedSniffInterface;

/**
 * Resolver.
 */
interface ResolverInterface
{

    /**
     * Resolve a  non-empty string value.
     *
     * @param DotSeparatedSniffInterface $dotSeparatedSniff the sniff name to resolve
     *
     * @return non-empty-string
     */
    public function resolve(DotSeparatedSniffInterface $dotSeparatedSniff);
}
