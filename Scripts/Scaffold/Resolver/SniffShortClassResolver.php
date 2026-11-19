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
use PHPCSDevTools\Tests\Scaffold\Resolver\ShortClassResolver\SniffShortClassResolverTest;

/**
 * Resolves the short class name for a sniff class.
 *
 * @see SniffShortClassResolverTest
 */
final class SniffShortClassResolver implements SniffShortClassResolverInterface
{

    /**
     * Resolve the short class name for a sniff class.
     *
     * @param DotSeparatedSniffInterface $dotSeparatedSniff the sniff name to resolve
     *
     * @return non-empty-string
     */
    public function resolve(DotSeparatedSniffInterface $dotSeparatedSniff)
    {
        return $dotSeparatedSniff->getSniff()->toString() . 'Sniff';
    }
}
