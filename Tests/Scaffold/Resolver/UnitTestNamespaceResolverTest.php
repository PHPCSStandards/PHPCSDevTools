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
use PHPCSDevTools\Scripts\Scaffold\Resolver\UnitTestNamespaceResolver;
use PHPCSDevTools\Scripts\Scaffold\Standard\NamespaceName;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;

/**
 * Test the UnitTestNamespaceResolver class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Resolver\UnitTestNamespaceResolver
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\DotSeparatedSniff
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Category
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Name
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\NamespaceName
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Sniff
 */
final class UnitTestNamespaceResolverTest extends AbstractTestcase
{

    /**
     * Verify UnitTestNamespaceResolver implements its required interfaces.
     *
     * @return void
     */
    public function testImplementsRequiredInterfaces()
    {
        $unitTestNamespaceResolver = new UnitTestNamespaceResolver($this->createMockNamespaceNameProvider());

        self::assertInstanceOf(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestNamespaceResolverInterface',
            $unitTestNamespaceResolver
        );
        self::assertInstanceOf(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\ResolverInterface',
            $unitTestNamespaceResolver
        );
    }

    /**
     * It resolves the namespace for a unit test class.
     *
     * @return void
     */
    public function testResolveBuildsTheUnitTestNamespace()
    {
        $dotSeparatedSniff     = DotSeparatedSniff::fromString('Standard.Category.MySniff');
        $namespaceNameProvider = $this->createMockNamespaceNameProvider(function ($mock) use ($dotSeparatedSniff) {
            $mock->expects(self::once())
                ->method('provide')
                ->with($dotSeparatedSniff->getStandard())
                ->willReturn(NamespaceName::fromString('Vendor\\Standard'));
        });

        $unitTestNamespaceResolver = new UnitTestNamespaceResolver($namespaceNameProvider);

        self::assertSame('Vendor\\Standard\\Tests\\Category', $unitTestNamespaceResolver->resolve($dotSeparatedSniff));
    }
}
