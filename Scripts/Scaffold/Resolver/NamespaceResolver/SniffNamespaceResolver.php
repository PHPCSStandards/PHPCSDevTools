<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Resolver\NamespaceResolver;

use PHPCSDevTools\Scripts\Scaffold\SniffNameInterface;

final class SniffNamespaceResolver implements SniffNamespaceResolverInterface
{

    /**
     * Returns the namespace for the sniff class.
     *
     * @return non-empty-string
     */
    public function resolve(SniffNameInterface $sniffName)
    {
        return \implode('\\', [
            $sniffName->getNamespace(),
            $sniffName->getStandard(),
            'Sniffs',
            $sniffName->getCategory(),
        ]);
    }
}
