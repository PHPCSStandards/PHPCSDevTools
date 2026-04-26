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
use PHPCSDevTools\Scripts\Scaffold\Standard\RulesetInterface;

/**
 * Reads ruleset.xml files using the FilesystemInterface.
 *
 * @implements RulesetReaderInterface
 */
final class RulesetReader implements RulesetReaderInterface
{

    /**
     * The filesystem used to read ruleset files.
     *
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * Create a new RulesetReader instance.
     *
     * @param FilesystemInterface $filesystem the filesystem used to read ruleset files
     */
    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Reads the contents of a ruleset.xml file.
     *
     * @param RulesetInterface $ruleset Path to the ruleset.xml file
     *
     * @return string XML contents
     */
    public function read(RulesetInterface $ruleset)
    {
        return $this->filesystem->read($ruleset->toString());
    }
}
