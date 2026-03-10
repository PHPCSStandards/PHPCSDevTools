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

use PHPCSDevTools\Scripts\Scaffold\Resolver\NamespaceResolver\UnitTestNamespaceResolver;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;

/**
 * Test the UnitTestNamespaceResolver class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Resolver\NamespaceResolver\UnitTestNamespaceResolver
 */
final class UnitTestNamespaceResolverTest extends AbstractTestcase
{

    /**
     * It resolves the namespace for a unit test class.
     *
     * @return void
     */
    public function testResolveBuildsTheUnitTestNamespace()
    {
        $sniffName = $this->createMockSniffName(function ($mock) {
            $mock->expects(self::once())->method('getNamespace')->willReturn('Vendor');
            $mock->expects(self::once())->method('getStandard')->willReturn('Standard');
            $mock->expects(self::once())->method('getCategory')->willReturn('Category');
        });

        $resolver = new UnitTestNamespaceResolver();

        self::assertSame('Vendor\Standard\Tests\Category', $resolver->resolve($sniffName));
    }
}
