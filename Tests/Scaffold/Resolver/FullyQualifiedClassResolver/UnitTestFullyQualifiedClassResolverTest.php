<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests\Scaffold\Resolver\FullyQualifiedClassResolver;

use PHPCSDevTools\Scripts\Scaffold\Resolver\FullyQualifiedClassResolver\UnitTestFullyQualifiedClassResolver;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;

/**
 * @covers \PHPCSDevTools\Scripts\Scaffold\Resolver\FullyQualifiedClassResolver\UnitTestFullyQualifiedClassResolver
 */
final class UnitTestFullyQualifiedClassResolverTest extends AbstractTestcase
{

    /**
     * It resolves the fully qualified class name for a unit test.
     *
     * @return void
     */
    public function testResolveBuildsTheUnitTestFullyQualifiedClassName()
    {
        $sniffName = $this->createMockSniffName(function ($mock) {
            $mock->expects(self::once())->method('getNamespace')->willReturn('Vendor');
            $mock->expects(self::once())->method('getStandard')->willReturn('Standard');
            $mock->expects(self::once())->method('getCategory')->willReturn('Category');
            $mock->expects(self::once())->method('getSniff')->willReturn('MySniff');
        });

        $resolver = new UnitTestFullyQualifiedClassResolver();

        self::assertSame('Vendor\Standard\Tests\Category\MySniffUnitTest', $resolver->resolve($sniffName));
    }
}
