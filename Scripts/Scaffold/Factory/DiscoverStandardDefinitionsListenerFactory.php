<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Factory;

use PHPCSDevTools\Scripts\Scaffold\ContainerInterface;
use PHPCSDevTools\Scripts\Scaffold\Listener\ApplicationConstructedEvent\DiscoverStandardDefinitionsListener;

/**
 * Creates standard discovery listeners for the scaffold container.
 *
 *  @implements FactoryInterface<DiscoverStandardDefinitionsListener>
 */
final class DiscoverStandardDefinitionsListenerFactory implements FactoryInterface
{

    /**
     * Create a standard discovery listener instance.
     *
     * @param ContainerInterface $container the service container
     *
     * @return DiscoverStandardDefinitionsListener
     */
    public function __invoke(ContainerInterface $container)
    {
        return new DiscoverStandardDefinitionsListener(
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\Ruleset\\RulesetFinderInterface'),
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\Ruleset\\RulesetParserInterface'),
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\Collection\\StandardCollectionInterface')
        );
    }
}
