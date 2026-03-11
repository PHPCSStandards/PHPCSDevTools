<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests\Scaffold\Resolver\PathResolver;

use PHPCSDevTools\Scripts\Scaffold\Resolver\PathResolver\DocsPathResolver;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;

/**
 * Test the DocsPathResolver class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Resolver\PathResolver\DocsPathResolver
 */
final class DocsPathResolverTest extends AbstractTestcase
{

    /**
     * It resolves the docs file path.
     *
     * @return void
     */
    public function testResolveBuildsTheDocumentationPath()
    {
        $sniffName = $this->createMockSniffName(function ($mock) {
            $mock->expects(self::once())->method('getStandard')->willReturn('Standard');
            $mock->expects(self::once())->method('getCategory')->willReturn('Category');
            $mock->expects(self::once())->method('getSniff')->willReturn('MySniff');
        });
        $workspace = $this->createMockWorkspace(function ($mock) {
            $mock->expects(self::once())->method('getPath')->willReturn(\DIRECTORY_SEPARATOR . 'project');
        });

        $resolver = new DocsPathResolver();

        self::assertSame(
            \DIRECTORY_SEPARATOR . \implode(\DIRECTORY_SEPARATOR, [
                'project',
                'Standard',
                'Docs',
                'Category',
                'MySniffStandard.xml',
            ]),
            $resolver->resolve($sniffName, $workspace)
        );
    }
}
