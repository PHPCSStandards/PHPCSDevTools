<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests\Scaffold\Resolver\ShortClassResolver;

use PHPCSDevTools\Scripts\Scaffold\Resolver\ShortClassResolver\UnitTestShortClassResolver;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;

/**
 * Test the UnitTestShortClassResolver class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Resolver\ShortClassResolver\UnitTestShortClassResolver
 */
final class UnitTestShortClassResolverTest extends AbstractTestcase
{

    /**
     * It resolves the short class name for a unit test.
     *
     * @return void
     */
    public function testResolveBuildsTheUnitTestShortClassName()
    {
        $sniffName = $this->createMockSniffName(function ($mock) {
            $mock->expects(self::once())->method('getSniff')->willReturn('MySniff');
        });

        $resolver = new UnitTestShortClassResolver();

        self::assertSame('MySniffUnitTest', $resolver->resolve($sniffName));
    }
}
