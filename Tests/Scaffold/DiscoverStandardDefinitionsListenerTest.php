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

use PHPCSDevTools\Scripts\Scaffold\Event\ApplicationConstructedEvent;
use PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationConstructedEvent\DiscoverStandardDefinitionsListener;

/**
 * Test the DiscoverStandardDefinitionsListener class.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationConstructedEvent\DiscoverStandardDefinitionsListener
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\Event\ApplicationConstructedEvent
 */
final class DiscoverStandardDefinitionsListenerTest extends AbstractTestcase
{

    /**
     * Verify DiscoverStandardDefinitionsListener implements ListenerInterface.
     *
     * @return void
     */
    public function testImplementsRequiredInterfaces()
    {
        $rulesetFinder = $this->createMockObject(
            'PHPCSDevTools\\Scripts\\Scaffold\\Ruleset\\RulesetFinderInterface'
        );

        $rulesetParser = $this->createMockObject(
            'PHPCSDevTools\\Scripts\\Scaffold\\Ruleset\\RulesetParserInterface'
        );

        $standardCollection = $this->createMockObject(
            'PHPCSDevTools\\Scripts\\Scaffold\\Collection\\StandardCollectionInterface'
        );

        $discoverStandardDefinitionsListener = new DiscoverStandardDefinitionsListener(
            $rulesetFinder,
            $rulesetParser,
            $standardCollection
        );

        self::assertInstanceOf(
            'PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ListenerInterface',
            $discoverStandardDefinitionsListener
        );
    }

    /**
     * Verify discovered ruleset metadata is registered in the standard collection.
     *
     * @return void
     */
    public function testRegistersDiscoveredStandardMetadataInTheCollection()
    {
        $standardMetadata = $this->createMockObject('PHPCSDevTools\\Scripts\\Scaffold\\StandardInterface');
        $ruleset          = $this->createMockObject('PHPCSDevTools\\Scripts\\Scaffold\\Standard\\RulesetInterface');
        $workspace        = $this->createMockWorkspace();

        $rulesetFinder = $this->createMockObject(
            'PHPCSDevTools\\Scripts\\Scaffold\\Ruleset\\RulesetFinderInterface',
            function ($mock) use ($workspace, $ruleset) {
                $mock->expects(self::once())
                    ->method('find')
                    ->with($workspace)
                    ->willReturn([$ruleset]);
            }
        );

        $rulesetParser = $this->createMockObject(
            'PHPCSDevTools\\Scripts\\Scaffold\\Ruleset\\RulesetParserInterface',
            function ($mock) use ($ruleset, $standardMetadata) {
                $mock->expects(self::once())
                    ->method('parse')
                    ->with($ruleset)
                    ->willReturn($standardMetadata);
            }
        );

        $standardCollection = $this->createMockObject(
            'PHPCSDevTools\\Scripts\\Scaffold\\Collection\\StandardCollectionInterface',
            function ($mock) use ($standardMetadata) {
                $mock->expects(self::once())
                    ->method('add')
                    ->with($standardMetadata);
            }
        );

        $discoverStandardDefinitionsListener = new DiscoverStandardDefinitionsListener(
            $rulesetFinder,
            $rulesetParser,
            $standardCollection
        );

        $discoverStandardDefinitionsListener(new ApplicationConstructedEvent($workspace));
    }
}
