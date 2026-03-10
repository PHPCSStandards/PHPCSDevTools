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

use PHPCSDevTools\Scripts\Scaffold\Resolver\ShortClassResolver\SniffShortClassResolver;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;

/**
 * Test the SniffShortClassResolver class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Resolver\ShortClassResolver\SniffShortClassResolver
 */
final class SniffShortClassResolverTest extends AbstractTestcase
{

    /**
     * It resolves the short class name for a sniff.
     *
     * @return void
     */
    public function testResolveBuildsTheSniffShortClassName()
    {
        $sniffName = $this->createMockSniffName(function ($mock) {
            $mock->expects(self::once())->method('getSniff')->willReturn('MySniff');
        });

        $resolver = new SniffShortClassResolver();

        self::assertSame('MySniffSniff', $resolver->resolve($sniffName));
    }
}
