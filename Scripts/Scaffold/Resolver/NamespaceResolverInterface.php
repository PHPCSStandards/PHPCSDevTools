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

use PHPCSDevTools\Scripts\Scaffold\ResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\SniffNameInterface;

interface NamespaceResolverInterface extends ResolverInterface
{

    /**
     * Resolve the namespace for the given sniff name.
     *
     * The namespace should be returned in the format `Vendor\Package\SubNamespace`.
     *
     * The namespace should not have a leading backslash, and should not have a trailing backslash.
     *
     * @param SniffNameInterface $sniffName the sniff name for which the namespace should be resolved
     *
     * @return non-empty-string
     */
    public function resolve(SniffNameInterface $sniffName);
}
