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
use PHPCSDevTools\Scripts\Scaffold\Resolver\UnitTestFullyQualifiedClassResolver;
use PHPCSDevTools\Scripts\Scaffold\Standard\NamespaceName;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;

/**
 * Test the UnitTestFullyQualifiedClassResolver class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Resolver\UnitTestFullyQualifiedClassResolver
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\DotSeparatedSniff
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Category
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Name
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\NamespaceName
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Sniff
 */
final class UnitTestFullyQualifiedClassResolverTest extends AbstractTestcase
{

    /**
     * Verify UnitTestFullyQualifiedClassResolver implements its required interfaces.
     *
     * @return void
     */
    public function testImplementsRequiredInterfaces()
    {
        $unitTestFullyQualifiedClassResolver = new UnitTestFullyQualifiedClassResolver(
            $this->createMockNamespaceNameProvider()
        );

        self::assertInstanceOf(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestFullyQualifiedClassResolverInterface',
            $unitTestFullyQualifiedClassResolver
        );
        self::assertInstanceOf(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\ResolverInterface',
            $unitTestFullyQualifiedClassResolver
        );
    }

    /**
     * It resolves the fully qualified class name for a unit test.
     *
     * @return void
     */
    public function testResolveBuildsTheUnitTestFullyQualifiedClassName()
    {
        $dotSeparatedSniff     = DotSeparatedSniff::fromString('Standard.Category.MySniff');
        $namespaceNameProvider = $this->createMockNamespaceNameProvider(function ($mock) use ($dotSeparatedSniff) {
            $mock->expects(self::once())
                ->method('provide')
                ->with($dotSeparatedSniff->getStandard())
                ->willReturn(NamespaceName::fromString('Vendor\\Standard'));
        });

        $unitTestFullyQualifiedClassResolver = new UnitTestFullyQualifiedClassResolver($namespaceNameProvider);

        self::assertSame(
            'Vendor\\Standard\\Tests\\Category\\MySniffUnitTest',
            $unitTestFullyQualifiedClassResolver->resolve($dotSeparatedSniff)
        );
    }
}
