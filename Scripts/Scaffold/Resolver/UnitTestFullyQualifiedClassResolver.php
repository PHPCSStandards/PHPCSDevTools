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
use PHPCSDevTools\Tests\Scaffold\Resolver\FullyQualifiedClassResolver\UnitTestFullyQualifiedClassResolverTest;

/**
 * Resolves the fully qualified class name for a unit test class.
 *
 * @see UnitTestFullyQualifiedClassResolverTest
 */
final class UnitTestFullyQualifiedClassResolver implements UnitTestFullyQualifiedClassResolverInterface
{

    /**
     * The namespace provider to resolve against.
     *
     * @var NamespaceNameProviderInterface
     */
    private $namespaceNameProvider;

    /**
     * Create a new unit test fully qualified class resolver instance.
     *
     * @param NamespaceNameProviderInterface $namespaceNameProvider the namespace provider to resolve against
     */
    public function __construct(NamespaceNameProviderInterface $namespaceNameProvider)
    {
        $this->namespaceNameProvider = $namespaceNameProvider;
    }

    /**
     * Resolve the fully qualified class name for a unit test class.
     *
     * @param DotSeparatedSniffInterface $dotSeparatedSniff the sniff name to resolve
     *
     * @return non-empty-string
     */
    public function resolve(DotSeparatedSniffInterface $dotSeparatedSniff)
    {
        $namespace = $this->namespaceNameProvider->provide($dotSeparatedSniff->getStandard())->toString();

        return \implode('\\', [
            $namespace,
            'Tests',
            $dotSeparatedSniff->getCategory()->toString(),
            $dotSeparatedSniff->getSniff()->toString() . 'UnitTest',
        ]);
    }
}
