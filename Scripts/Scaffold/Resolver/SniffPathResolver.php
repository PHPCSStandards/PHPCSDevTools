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
use PHPCSDevTools\Scripts\Scaffold\Provider\DirectoryProviderInterface;
use PHPCSDevTools\Tests\Scaffold\Resolver\PathResolver\SniffPathResolverTest;

/**
 * Resolves the sniff class file path for a sniff.
 *
 * @see SniffPathResolverTest
 */
final class SniffPathResolver implements SniffPathResolverInterface
{

    /**
     * The directory provider.
     *
     * @var DirectoryProviderInterface
     */
    private $directoryProvider;

    /**
     * Create a new SniffPathResolver instance.
     *
     * @param DirectoryProviderInterface $directoryProvider the directory provider
     */
    public function __construct(DirectoryProviderInterface $directoryProvider)
    {
        $this->directoryProvider = $directoryProvider;
    }

    /**
     * Resolve the sniff class file path for a sniff.
     *
     * @param DotSeparatedSniffInterface $dotSeparatedSniff the sniff name to resolve
     *
     * @return non-empty-string
     */
    public function resolve(DotSeparatedSniffInterface $dotSeparatedSniff)
    {
        return \implode(\DIRECTORY_SEPARATOR, [
            $this->directoryProvider->provide($dotSeparatedSniff->getStandard())->toString(),
            'Sniffs',
            $dotSeparatedSniff->getCategory()->toString(),
            $dotSeparatedSniff->getSniff()->toString() . 'Sniff.php',
        ]);
    }
}
