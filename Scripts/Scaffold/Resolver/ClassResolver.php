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

final class ClassResolver
{
    public function resolveUnitTestClass(SniffName $sniffName)
    {
        return $sniffName->getSniff() . 'UnitTest';
    }

    public function resolveSniffClass(SniffName $sniffName)
    {
        return $sniffName->getSniff() . 'Sniff';
    }
}
