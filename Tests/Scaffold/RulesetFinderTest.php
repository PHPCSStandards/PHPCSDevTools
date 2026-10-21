<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests\Scaffold;

use PHPCSDevTools\Scripts\Scaffold\Ruleset\RulesetFinder;

/**
 * Test the RulesetFinder class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Ruleset\RulesetFinder
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\Ruleset\RulesetFinderInterface
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Ruleset
 * @uses \PHPCSDevTools\Scripts\Scaffold\FilesystemInterface
 * @uses \PHPCSDevTools\Scripts\Scaffold\WorkspaceInterface
 */
final class RulesetFinderTest extends AbstractTestcase
{

    /**
     * Verify ruleset discovery delegates to the filesystem matcher.
     *
     * @return void
     */
    public function testFindReturnsTheMatchedRulesetPaths()
    {
        $workspace = $this->createMockWorkspace(function ($mock) {
            $mock->expects(self::once())
                ->method('toString')
                ->willReturn('/workspace');
        });

        $filesystem = $this->createMockFilesystem(function ($mock) {
            $mock->expects(self::once())
                ->method('find')
                ->with('/workspace', '#(?:^|[/\\\\])ruleset\.xml$#u')
                ->willReturn(['/workspace/Standard/ruleset.xml', '/workspace/AnotherStandard/ruleset.xml']);
        });

        $rulesetFinder = new RulesetFinder($filesystem);
        $rulesets      = $rulesetFinder->find($workspace);

        self::assertCount(2, $rulesets);
        self::assertInstanceOf('PHPCSDevTools\\Scripts\\Scaffold\\Standard\\RulesetInterface', $rulesets[0]);
        self::assertSame('/workspace/Standard/ruleset.xml', $rulesets[0]->toString());
        self::assertSame('/workspace/AnotherStandard/ruleset.xml', $rulesets[1]->toString());
    }

    /**
     * Verify RulesetFinder implements its interface.
     *
     * @return void
     */
    public function testImplementsRulesetFinderInterface()
    {
        $rulesetFinder = new RulesetFinder($this->createMockFilesystem());

        self::assertInstanceOf('PHPCSDevTools\Scripts\Scaffold\Ruleset\RulesetFinderInterface', $rulesetFinder);
    }
}
