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
use PHPCSDevTools\Scripts\Scaffold\Resolver\DocsPathResolver;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;

/**
 * Test the DocsPathResolver class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Resolver\DocsPathResolver
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\DotSeparatedSniff
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Category
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Name
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Sniff
 */
final class DocsPathResolverTest extends AbstractTestcase
{

    /**
     * Verify DocsPathResolver implements its required interfaces.
     *
     * @return void
     */
    public function testImplementsRequiredInterfaces()
    {
        $docsPathResolver = new DocsPathResolver($this->createMockDirectoryProvider());

        self::assertInstanceOf(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\DocsPathResolverInterface',
            $docsPathResolver
        );
        self::assertInstanceOf('PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\ResolverInterface', $docsPathResolver);
    }

    /**
     * It resolves the docs file path.
     *
     * @return void
     */
    public function testResolveBuildsTheDocumentationPath()
    {
        $sniffName         = DotSeparatedSniff::fromString('Standard.Category.MySniff');
        $directoryProvider = $this->createMockDirectoryProvider(function ($mock) use ($sniffName) {
            $standardDirectory = $this->createMockObject(
                'PHPCSDevTools\\Scripts\\Scaffold\\Standard\\DirectoryInterface'
            );
            $standardDirectory->expects(self::once())->method('toString')->willReturn(\DIRECTORY_SEPARATOR . 'project');

            $mock->expects(self::once())->method('provide')->with($sniffName->getStandard())->willReturn(
                $standardDirectory
            );
        });

        $docsPathResolver = new DocsPathResolver($directoryProvider);

        self::assertSame(
            \DIRECTORY_SEPARATOR . \implode(\DIRECTORY_SEPARATOR, [
                'project',
                'Docs',
                'Category',
                'MySniffStandard.xml',
            ]),
            $docsPathResolver->resolve($sniffName)
        );
    }
}
