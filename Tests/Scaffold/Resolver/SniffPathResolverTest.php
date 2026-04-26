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
use PHPCSDevTools\Scripts\Scaffold\Resolver\SniffPathResolver;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;

/**
 * Test the SniffPathResolver class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Resolver\SniffPathResolver
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\DotSeparatedSniff
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Category
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Name
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Sniff
 */
final class SniffPathResolverTest extends AbstractTestcase
{

    /**
     * Verify SniffPathResolver implements its required interfaces.
     *
     * @return void
     */
    public function testImplementsRequiredInterfaces()
    {
        $sniffPathResolver = new SniffPathResolver($this->createMockDirectoryProvider());

        self::assertInstanceOf(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\SniffPathResolverInterface',
            $sniffPathResolver
        );
        self::assertInstanceOf('PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\ResolverInterface', $sniffPathResolver);
    }

    /**
     * It resolves the sniff file path.
     *
     * @return void
     */
    public function testResolveBuildsTheSniffFilePath()
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

        $sniffPathResolver = new SniffPathResolver($directoryProvider);

        self::assertSame(
            \DIRECTORY_SEPARATOR . \implode(\DIRECTORY_SEPARATOR, [
                'project',
                'Sniffs',
                'Category',
                'MySniffSniff.php',
            ]),
            $sniffPathResolver->resolve($sniffName)
        );
    }
}
