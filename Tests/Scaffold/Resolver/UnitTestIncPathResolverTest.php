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
use PHPCSDevTools\Scripts\Scaffold\Resolver\UnitTestIncPathResolver;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;

/**
 * Test the UnitTestIncPathResolver class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Resolver\UnitTestIncPathResolver
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\DotSeparatedSniff
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Category
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Name
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Sniff
 */
final class UnitTestIncPathResolverTest extends AbstractTestcase
{

    /**
     * Verify UnitTestIncPathResolver implements its required interfaces.
     *
     * @return void
     */
    public function testImplementsRequiredInterfaces()
    {
        $unitTestIncPathResolver = new UnitTestIncPathResolver($this->createMockDirectoryProvider());

        self::assertInstanceOf(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestIncPathResolverInterface',
            $unitTestIncPathResolver
        );
        self::assertInstanceOf(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\ResolverInterface',
            $unitTestIncPathResolver
        );
    }

    /**
     * It resolves the fixture path.
     *
     * @return void
     */
    public function testResolveBuildsTheFixturePath()
    {
        $dotSeparatedSniff = DotSeparatedSniff::fromString('Standard.Category.MySniff');
        $directoryProvider = $this->createMockDirectoryProvider(function ($mock) use ($dotSeparatedSniff) {
            $standardDirectory = $this->createMockObject(
                'PHPCSDevTools\\Scripts\\Scaffold\\Standard\\DirectoryInterface'
            );
            $standardDirectory->expects(self::once())->method('toString')->willReturn(\DIRECTORY_SEPARATOR . 'project');

            $mock->expects(self::once())->method('provide')->with($dotSeparatedSniff->getStandard())->willReturn(
                $standardDirectory
            );
        });

        $unitTestIncPathResolver = new UnitTestIncPathResolver($directoryProvider);

        self::assertSame(
            \DIRECTORY_SEPARATOR . \implode(\DIRECTORY_SEPARATOR, [
                'project',
                'Tests',
                'Category',
                'MySniffUnitTest.inc',
            ]),
            $unitTestIncPathResolver->resolve($dotSeparatedSniff)
        );
    }
}
