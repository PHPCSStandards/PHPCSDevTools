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
use PHPCSDevTools\Scripts\Scaffold\Resolver\SniffFullyQualifiedClassResolver;
use PHPCSDevTools\Scripts\Scaffold\Standard\NamespaceName;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;

/**
 * Test the SniffFullyQualifiedClassResolver class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Resolver\SniffFullyQualifiedClassResolver
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\DotSeparatedSniff
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Category
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Name
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\NamespaceName
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Sniff
 */
final class SniffFullyQualifiedClassResolverTest extends AbstractTestcase
{

    /**
     * Verify SniffFullyQualifiedClassResolver implements its required interfaces.
     *
     * @return void
     */
    public function testImplementsRequiredInterfaces()
    {
        $sniffFullyQualifiedClassResolver = new SniffFullyQualifiedClassResolver(
            $this->createMockNamespaceNameProvider()
        );

        self::assertInstanceOf(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\SniffFullyQualifiedClassResolverInterface',
            $sniffFullyQualifiedClassResolver
        );
        self::assertInstanceOf(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\ResolverInterface',
            $sniffFullyQualifiedClassResolver
        );
    }

    /**
     * It resolves the fully qualified class name for a sniff.
     *
     * @return void
     */
    public function testResolveBuildsTheSniffFullyQualifiedClassName()
    {
        $sniffName             = DotSeparatedSniff::fromString('Standard.Category.MySniff');
        $namespaceNameProvider = $this->createMockNamespaceNameProvider(function ($mock) use ($sniffName) {
            $mock->expects(self::once())
                ->method('provide')
                ->with($sniffName->getStandard())
                ->willReturn(NamespaceName::fromString('Vendor\\Standard'));
        });

        $sniffFullyQualifiedClassResolver = new SniffFullyQualifiedClassResolver($namespaceNameProvider);

        self::assertSame(
            'Vendor\\Standard\\Sniffs\\Category\\MySniffSniff',
            $sniffFullyQualifiedClassResolver->resolve($sniffName)
        );
    }
}
