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

use PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException;
use PHPCSDevTools\Scripts\Scaffold\Standard;
use PHPCSDevTools\Scripts\Scaffold\Standard\Directory;
use PHPCSDevTools\Scripts\Scaffold\Standard\Name;
use PHPCSDevTools\Scripts\Scaffold\Standard\NamespaceName;
use PHPCSDevTools\Scripts\Scaffold\Standard\RulesetInterface;
use PHPCSDevTools\Scripts\Scaffold\StandardInterface;

/**
 * Parses a ruleset.xml file and returns standard metadata for the referenced standard.
 */
final class RulesetParser implements RulesetParserInterface
{

    /**
     * The ruleset reader used to read ruleset files.
     *
     * @var RulesetReaderInterface
     */
    private $rulesetReader;

    /**
     * Create a new RulesetParser instance.
     *
     * @param RulesetReaderInterface $rulesetReader the ruleset reader used to read ruleset files
     */
    public function __construct(RulesetReaderInterface $rulesetReader)
    {
        $this->rulesetReader = $rulesetReader;
    }

    /**
     * Parses the XML contents of a ruleset.xml file.
     *
     * @param RulesetInterface $ruleset Path to the ruleset.xml file
     *
     * @throws ScaffolderException
     *
     * @return StandardInterface
     */
    public function parse(RulesetInterface $ruleset)
    {
        $xml = $this->rulesetReader->read($ruleset);

        $path = $ruleset->toString();

        $domDocument = new \DOMDocument();
        if (@$domDocument->loadXML($xml) === false) {
            throw new ScaffolderException(\sprintf('Failed to parse ruleset file "%s".', $path));
        }

        $rulesetElement = $domDocument->getElementsByTagName('ruleset')->item(0);
        if (($rulesetElement instanceof \DOMElement) === false) {
            throw new ScaffolderException(\sprintf('Failed to parse ruleset file "%s".', $path));
        }

        if ($rulesetElement->hasAttribute('name') === false) {
            throw new ScaffolderException(\sprintf('Failed to determine standard name from ruleset file "%s".', $path));
        }

        $name = Name::fromString(\trim($rulesetElement->getAttribute('name')));

        if ($rulesetElement->hasAttribute('namespace') === false) {
            $namespaceName =  NamespaceName::fromString($name->toString());
        } else {
            $namespaceName = NamespaceName::fromString(\trim($rulesetElement->getAttribute('namespace')));
        }

        $directory = Directory::fromString(\dirname($path));

        return new Standard($directory, $name, $namespaceName, $ruleset);
    }
}
