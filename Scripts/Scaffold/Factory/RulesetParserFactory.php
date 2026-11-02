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
use PHPCSDevTools\Scripts\Scaffold\Ruleset\RulesetParser;

/**
 * Creates ruleset parser instances for the scaffold container.
 *
 * @implements FactoryInterface<RulesetParser>
 */
final class RulesetParserFactory implements FactoryInterface
{

    /**
     * Create a ruleset parser instance.
     *
     * @param ContainerInterface $container the service container
     *
     * @return RulesetParser
     */
    public function __invoke(ContainerInterface $container)
    {
        return new RulesetParser(
            $container->get('PHPCSDevTools\\Scripts\\Scaffold\\Ruleset\\RulesetReaderInterface')
        );
    }
}
