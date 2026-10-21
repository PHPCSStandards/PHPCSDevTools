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
use PHPCSDevTools\Scripts\Scaffold\Ruleset\RulesetFinder;

/**
 * Creates ruleset finder instances for the scaffold container.
 *
 * @implements FactoryInterface<RulesetFinder>
 */
final class RulesetFinderFactory implements FactoryInterface
{

    /**
     * Create a ruleset finder instance.
     *
     * @param ContainerInterface $container the service container
     *
     * @return RulesetFinder
     */
    public function __invoke(ContainerInterface $container)
    {
        return new RulesetFinder($container->get('PHPCSDevTools\\Scripts\\Scaffold\\FilesystemInterface'));
    }
}
