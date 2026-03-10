<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests\Scaffold\Resolver\NamespaceResolver;

use PHPCSDevTools\Scripts\Scaffold\Resolver\NamespaceResolver\SniffNamespaceResolver;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;

/**
 * Test the SniffNamespaceResolver class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Resolver\NamespaceResolver\SniffNamespaceResolver
 */
final class SniffNamespaceResolverTest extends AbstractTestcase
{

    /**
     * It resolves the namespace for a sniff class.
     *
     * @return void
     */
    public function testResolveBuildsTheSniffNamespace()
    {
        $sniffName = $this->createMockSniffName(function ($mock) {
            $mock->expects(self::once())->method('getNamespace')->willReturn('Vendor');
            $mock->expects(self::once())->method('getStandard')->willReturn('Standard');
            $mock->expects(self::once())->method('getCategory')->willReturn('Category');
        });

        $resolver = new SniffNamespaceResolver();

        self::assertSame('Vendor\Standard\Sniffs\Category', $resolver->resolve($sniffName));
    }
}
