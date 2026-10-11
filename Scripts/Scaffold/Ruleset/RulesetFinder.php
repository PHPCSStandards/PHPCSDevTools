<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Ruleset;

use PHPCSDevTools\Scripts\Scaffold\FilesystemInterface;
use PHPCSDevTools\Scripts\Scaffold\Standard\Ruleset;
use PHPCSDevTools\Scripts\Scaffold\Standard\RulesetInterface;
use PHPCSDevTools\Scripts\Scaffold\WorkspaceInterface;
use PHPCSDevTools\Tests\Scaffold\RulesetFinderTest;

/**
 * Finds PHPCS ruleset files within a workspace.
 *
 * @see RulesetFinderTest
 */
final class RulesetFinder implements RulesetFinderInterface
{
    const RULESET_XML = '#(?:^|[/\\\\])ruleset\.xml$#u';

    /**
     * The filesystem used to search for rulesets.
     *
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * Create a new ruleset finder.
     *
     * @param FilesystemInterface $filesystem the filesystem used to search for rulesets
     */
    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Find all ruleset.xml files below the provided workspace.
     *
     * @param WorkspaceInterface $workspace the workspace to search
     *
     * @return list<RulesetInterface> a list of ruleset paths relative to the workspace
     */
    public function find(WorkspaceInterface $workspace)
    {
        $rulesets = [];

        foreach ($this->filesystem->find($workspace->toString(), self::RULESET_XML) as $ruleset) {
            $rulesets[] = Ruleset::fromString($ruleset);
        }

        return $rulesets;
    }
}
