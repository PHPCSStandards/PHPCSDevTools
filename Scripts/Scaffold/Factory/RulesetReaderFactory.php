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
use PHPCSDevTools\Scripts\Scaffold\Ruleset\RulesetReader;

/**
 * Creates ruleset reader instances for the scaffold container.
 *
 * @implements FactoryInterface<RulesetReader>
 */
final class RulesetReaderFactory implements FactoryInterface
{

    /**
     * Create a ruleset reader instance.
     *
     * @param ContainerInterface $container the service container
     *
     * @return RulesetReader
     */
    public function __invoke(ContainerInterface $container)
    {
        return new RulesetReader($container->get('PHPCSDevTools\\Scripts\\Scaffold\\FilesystemInterface'));
    }
}
