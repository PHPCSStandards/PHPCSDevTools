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

use PHPCSDevTools\Scripts\Scaffold\Standard\RulesetInterface;
use PHPCSDevTools\Scripts\Scaffold\WorkspaceInterface;

/**
 * Defines ruleset discovery for scaffold metadata and console workflows.
 */
interface RulesetFinderInterface
{

    /**
     * Find all ruleset.xml files below the provided workspace.
     *
     * @param WorkspaceInterface $workspace the workspace to search
     *
     * @return list<RulesetInterface> the ruleset files found
     */
    public function find(WorkspaceInterface $workspace);
}
