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

use PHPCSDevTools\Scripts\Scaffold\SniffName;

final class NamespaceResolver
{
    public function resolveUnitTestNamespace(SniffName $sniffName)
    {
        return implode('\\', [
            $sniffName->getNamespace(),
            $sniffName->getStandard(),
            'Tests',
            $sniffName->getCategory(),
        ]);
    }

    public function resolveSniffNamespace(SniffName $sniffName)
    {
        return implode('\\', [
            $sniffName->getNamespace(),
            $sniffName->getStandard(),
            'Sniffs',
            $sniffName->getCategory(),
        ]);
    }
}
