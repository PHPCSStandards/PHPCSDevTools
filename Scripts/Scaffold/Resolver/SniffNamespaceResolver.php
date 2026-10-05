<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Resolver;

use PHPCSDevTools\Scripts\Scaffold\DotSeparatedSniffInterface;
use PHPCSDevTools\Scripts\Scaffold\Provider\NamespaceNameProviderInterface;
use PHPCSDevTools\Tests\Scaffold\Resolver\NamespaceResolver\SniffNamespaceResolverTest;

/**
 * Resolves the namespace for a sniff class.
 *
 * @see SniffNamespaceResolverTest
 */
final class SniffNamespaceResolver implements SniffNamespaceResolverInterface
{

    /**
     * The namespace provider to resolve against.
     *
     * @var NamespaceNameProviderInterface
     */
    private $namespaceNameProvider;

    /**
     * Create a new sniff namespace resolver instance.
     *
     * @param NamespaceNameProviderInterface $namespaceNameProvider the namespace provider to resolve against
     */
    public function __construct(NamespaceNameProviderInterface $namespaceNameProvider)
    {
        $this->namespaceNameProvider = $namespaceNameProvider;
    }

    /**
     * Resolve the namespace for a sniff class.
     *
     * @param DotSeparatedSniffInterface $dotSeparatedSniff the sniff name to resolve
     *
     * @return non-empty-string
     */
    public function resolve(DotSeparatedSniffInterface $dotSeparatedSniff)
    {
        return \implode('\\', [
            $this->namespaceNameProvider->provide($dotSeparatedSniff->getStandard())->toString(),
            'Sniffs',
            $dotSeparatedSniff->getCategory()->toString(),
        ]);
    }
}
