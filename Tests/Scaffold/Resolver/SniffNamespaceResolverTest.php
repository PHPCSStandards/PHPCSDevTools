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
use PHPCSDevTools\Scripts\Scaffold\Resolver\SniffNamespaceResolver;
use PHPCSDevTools\Scripts\Scaffold\Standard\NamespaceName;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;

/**
 * Test the SniffNamespaceResolver class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Resolver\SniffNamespaceResolver
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\DotSeparatedSniff
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Category
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Name
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\NamespaceName
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Sniff
 */
final class SniffNamespaceResolverTest extends AbstractTestcase
{

    /**
     * Verify SniffNamespaceResolver implements its required interfaces.
     *
     * @return void
     */
    public function testImplementsRequiredInterfaces()
    {
        $sniffNamespaceResolver = new SniffNamespaceResolver($this->createMockNamespaceNameProvider());

        self::assertInstanceOf(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\SniffNamespaceResolverInterface',
            $sniffNamespaceResolver
        );
        self::assertInstanceOf(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\ResolverInterface',
            $sniffNamespaceResolver
        );
    }

    /**
     * It resolves the namespace for a sniff class.
     *
     * @return void
     */
    public function testResolveBuildsTheSniffNamespace()
    {
        $sniffName             = DotSeparatedSniff::fromString('Standard.Category.MySniff');
        $namespaceNameProvider = $this->createMockNamespaceNameProvider(function ($mock) use ($sniffName) {
            $mock->expects(self::once())
                ->method('provide')
                ->with($sniffName->getStandard())
                ->willReturn(NamespaceName::fromString('Vendor\\Standard'));
        });

        $sniffNamespaceResolver = new SniffNamespaceResolver($namespaceNameProvider);

        self::assertSame('Vendor\\Standard\\Sniffs\\Category', $sniffNamespaceResolver->resolve($sniffName));
    }
}
