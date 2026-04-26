<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests\Scaffold\Resolver;

use PHPCSDevTools\Scripts\Scaffold\DotSeparatedSniff;
use PHPCSDevTools\Scripts\Scaffold\Resolver\SniffShortClassResolver;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;

/**
 * Test the SniffShortClassResolver class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Resolver\SniffShortClassResolver
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\DotSeparatedSniff
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Category
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Name
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Sniff
 */
final class SniffShortClassResolverTest extends AbstractTestcase
{

    /**
     * Verify SniffShortClassResolver implements its required interfaces.
     *
     * @return void
     */
    public function testImplementsRequiredInterfaces()
    {
        $sniffShortClassResolver = new SniffShortClassResolver();

        self::assertInstanceOf(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\SniffShortClassResolverInterface',
            $sniffShortClassResolver
        );
        self::assertInstanceOf(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\ResolverInterface',
            $sniffShortClassResolver
        );
    }

    /**
     * It resolves the short class name for a sniff.
     *
     * @return void
     */
    public function testResolveBuildsTheSniffShortClassName()
    {
        $sniffShortClassResolver = new SniffShortClassResolver();
        $sniffName               = DotSeparatedSniff::fromString('Standard.Category.MySniff');

        self::assertSame('MySniffSniff', $sniffShortClassResolver->resolve($sniffName));
    }
}
