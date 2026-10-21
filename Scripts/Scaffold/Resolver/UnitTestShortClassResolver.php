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
use PHPCSDevTools\Tests\Scaffold\Resolver\ShortClassResolver\UnitTestShortClassResolverTest;

/**
 * Resolves the short class name for a unit test class.
 *
 * @see UnitTestShortClassResolverTest
 */
final class UnitTestShortClassResolver implements UnitTestShortClassResolverInterface
{

    /**
     * Resolve the short class name for a unit test class.
     *
     * @param DotSeparatedSniffInterface $dotSeparatedSniff the sniff name to resolve
     *
     * @return non-empty-string
     */
    public function resolve(DotSeparatedSniffInterface $dotSeparatedSniff)
    {
        return $dotSeparatedSniff->getSniff()->toString() . 'UnitTest';
    }
}
