<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold;

use PHPCSDevTools\Scripts\Scaffold\Standard\DirectoryInterface;
use PHPCSDevTools\Scripts\Scaffold\Standard\NameInterface;
use PHPCSDevTools\Scripts\Scaffold\Standard\NamespaceNameInterface;
use PHPCSDevTools\Scripts\Scaffold\Standard\RulesetInterface;

/**
 * Represents discovered metadata for a PHPCS standard.
 */
final class Standard implements StandardInterface
{

    /**
     * The standard directory.
     *
     * @var DirectoryInterface
     */
    private $directory;

    /**
     * The standard name.
     *
     * @var NameInterface
     */
    private $name;

    /**
     * The standard namespace, if any.
     *
     * @var NamespaceNameInterface
     */
    private $namespaceName;

    /**
     * The standard ruleset path.
     *
     * @var RulesetInterface
     */
    private $ruleset;

    /**
     * Create a standard value object.
     *
     * @param DirectoryInterface     $directory     the discovered standard directory
     * @param NameInterface          $name          the discovered standard name
     * @param NamespaceNameInterface $namespaceName the discovered standard namespace
     * @param RulesetInterface       $ruleset       the discovered ruleset path
     */
    public function __construct(
        DirectoryInterface $directory,
        NameInterface $name,
        NamespaceNameInterface $namespaceName,
        RulesetInterface $ruleset
    ) {
        $this->directory     = $directory;
        $this->name          = $name;
        $this->namespaceName = $namespaceName;
        $this->ruleset       = $ruleset;
    }

    /**
     * Get the standard directory metadata.
     *
     * @return DirectoryInterface
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * Get the standard name metadata.
     *
     * @return NameInterface
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the standard namespace metadata, if any.
     *
     * @return NamespaceNameInterface
     */
    public function getNamespaceName()
    {
        return $this->namespaceName;
    }

    /**
     * Get the standard ruleset metadata.
     *
     * @return RulesetInterface
     */
    public function getRuleset()
    {
        return $this->ruleset;
    }
}
