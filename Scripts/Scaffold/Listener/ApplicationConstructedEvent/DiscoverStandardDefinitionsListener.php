<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationConstructedEvent;

use PHPCSDevTools\Scripts\Scaffold\Collection\StandardCollectionInterface;
use PHPCSDevTools\Scripts\Scaffold\Event\ApplicationConstructedEvent;
use PHPCSDevTools\Scripts\Scaffold\Event\EventInterface;
use PHPCSDevTools\Scripts\Scaffold\Listener\ListenerInterface;
use PHPCSDevTools\Scripts\Scaffold\Ruleset\RulesetFinderInterface;
use PHPCSDevTools\Scripts\Scaffold\Ruleset\RulesetParserInterface;
use PHPCSDevTools\Tests\Scaffold\DiscoverStandardDefinitionsListenerTest;

/**
 * Discovers standard definitions from ruleset files inside the current workspace.
 *
 * @see DiscoverStandardDefinitionsListenerTest
 */
final class DiscoverStandardDefinitionsListener implements ListenerInterface
{

    /**
     * The ruleset finder used to discover ruleset files.
     *
     * @var RulesetFinderInterface
     */
    private $rulesetFinder;

    /**
     * The ruleset parser used to build metadata objects.
     *
     * @var RulesetParserInterface
     */
    private $rulesetParser;

    /**
     * The standard collection to which discovered standards will be added.
     *
     * @var StandardCollectionInterface
     */
    private $standardCollection;

    /**
     * Create a new standard discovery listener.
     *
     * @param RulesetFinderInterface      $rulesetFinder      the ruleset finder used to discover rulesets
     * @param RulesetParserInterface      $rulesetParser      the ruleset parser used to build metadata objects
     * @param StandardCollectionInterface $standardCollection the standard collection to which discovered standards will be added
     */
    public function __construct(
        RulesetFinderInterface $rulesetFinder,
        RulesetParserInterface $rulesetParser,
        StandardCollectionInterface $standardCollection
    ) {
        $this->rulesetFinder = $rulesetFinder;

        $this->rulesetParser = $rulesetParser;

        $this->standardCollection = $standardCollection;
    }

    /**
     * Handle the scaffold starting event.
     *
     * @param ApplicationConstructedEvent $event the event to handle
     *
     * @return void
     */
    public function __invoke(EventInterface $event)
    {
        \assert($event instanceof ApplicationConstructedEvent);

        foreach ($this->rulesetFinder->find($event->getWorkspace()) as $ruleset) {
            $this->standardCollection->add($this->rulesetParser->parse($ruleset));
        }
    }
}
