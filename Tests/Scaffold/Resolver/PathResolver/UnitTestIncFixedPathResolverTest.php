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

use PHPCSDevTools\Scripts\Scaffold\Resolver\PathResolver\UnitTestIncFixedPathResolver;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;

/**
 * Test the UnitTestIncFixedPathResolver class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Resolver\PathResolver\UnitTestIncFixedPathResolver
 */
final class UnitTestIncFixedPathResolverTest extends AbstractTestcase
{

    /**
     * It resolves the fixed fixture path.
     *
     * @return void
     */
    public function testResolveBuildsTheFixedFixturePath()
    {
        $sniffName = $this->createMockSniffName(function ($mock) {
            $mock->expects(self::once())->method('getStandard')->willReturn('Standard');
            $mock->expects(self::once())->method('getCategory')->willReturn('Category');
            $mock->expects(self::once())->method('getSniff')->willReturn('MySniff');
        });
        $workspace = $this->createMockWorkspace(function ($mock) {
            $mock->expects(self::once())->method('getPath')->willReturn(\DIRECTORY_SEPARATOR . 'project');
        });

        $resolver = new UnitTestIncFixedPathResolver();

        self::assertSame(
            \DIRECTORY_SEPARATOR . \implode(\DIRECTORY_SEPARATOR, [
                'project',
                'Standard',
                'Tests',
                'Category',
                'MySniffUnitTest.inc.fixed',
            ]),
            $resolver->resolve($sniffName, $workspace)
        );
    }
}
