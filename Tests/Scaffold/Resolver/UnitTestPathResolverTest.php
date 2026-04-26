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
use PHPCSDevTools\Scripts\Scaffold\Resolver\UnitTestPathResolver;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;

/**
 * Test the UnitTestPathResolver class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Resolver\UnitTestPathResolver
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\DotSeparatedSniff
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Category
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Name
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Sniff
 */
final class UnitTestPathResolverTest extends AbstractTestcase
{

    /**
     * Verify UnitTestPathResolver implements its required interfaces.
     *
     * @return void
     */
    public function testImplementsRequiredInterfaces()
    {
        $unitTestPathResolver = new UnitTestPathResolver($this->createMockDirectoryProvider());

        self::assertInstanceOf(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestPathResolverInterface',
            $unitTestPathResolver
        );
        self::assertInstanceOf(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\ResolverInterface',
            $unitTestPathResolver
        );
    }

    /**
     * It resolves the unit test file path.
     *
     * @return void
     */
    public function testResolveBuildsTheUnitTestFilePath()
    {
        $sniffName         = DotSeparatedSniff::fromString('Standard.Category.MySniff');
        $directoryProvider = $this->createMockDirectoryProvider(function ($mock) use ($sniffName) {
            $standardDirectory = $this->createMock(
                'PHPCSDevTools\\Scripts\\Scaffold\\Standard\\DirectoryInterface'
            );
            $standardDirectory->expects(self::once())->method('toString')->willReturn(\DIRECTORY_SEPARATOR . 'project');

            $mock->expects(self::once())->method('provide')->with($sniffName->getStandard())->willReturn(
                $standardDirectory
            );
        });

        $unitTestPathResolver = new UnitTestPathResolver($directoryProvider);

        self::assertSame(
            \DIRECTORY_SEPARATOR . \implode(\DIRECTORY_SEPARATOR, [
                'project',
                'Tests',
                'Category',
                'MySniffUnitTest.php',
            ]),
            $unitTestPathResolver->resolve($sniffName)
        );
    }
}
