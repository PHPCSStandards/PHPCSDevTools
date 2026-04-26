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
use PHPCSDevTools\Scripts\Scaffold\Resolver\UnitTestShortClassResolver;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;

/**
 * Test the UnitTestShortClassResolver class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Resolver\UnitTestShortClassResolver
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\DotSeparatedSniff
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Category
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Name
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Sniff
 */
final class UnitTestShortClassResolverTest extends AbstractTestcase
{

    /**
     * Verify UnitTestShortClassResolver implements its required interfaces.
     *
     * @return void
     */
    public function testImplementsRequiredInterfaces()
    {
        $unitTestShortClassResolver = new UnitTestShortClassResolver();

        self::assertInstanceOf(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestShortClassResolverInterface',
            $unitTestShortClassResolver
        );
        self::assertInstanceOf(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\ResolverInterface',
            $unitTestShortClassResolver
        );
    }

    /**
     * It resolves the short class name for a unit test.
     *
     * @return void
     */
    public function testResolveBuildsTheUnitTestShortClassName()
    {
        $unitTestShortClassResolver = new UnitTestShortClassResolver();
        $sniffName                  = DotSeparatedSniff::fromString('Standard.Category.MySniff');

        self::assertSame('MySniffUnitTest', $unitTestShortClassResolver->resolve($sniffName));
    }
}
