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

/**
 * Interface for reading ruleset.xml files using a filesystem abstraction.
 */
interface RulesetReaderInterface
{

    /**
     * Reads the contents of a ruleset.xml file.
     *
     * @param RulesetInterface $ruleset Path to the ruleset.xml file
     *
     * @return string XML contents
     */
    public function read(RulesetInterface $ruleset);
}
