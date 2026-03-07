<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Resolver\PathResolver;

use PHPCSDevTools\Scripts\Scaffold\SniffNameInterface;
use PHPCSDevTools\Scripts\Scaffold\WorkspaceInterface;

final class UnitTestIncPathResolver implements UnitTestIncPathResolverInterface
{

    /**
     * @return non-empty-string
     */
    public function resolve(SniffNameInterface $sniffName, WorkspaceInterface $workspace)
    {
        return \implode(\DIRECTORY_SEPARATOR, [
            $workspace->getPath(),
            $sniffName->getStandard(),
            'Tests',
            $sniffName->getCategory(),
            $sniffName->getSniff() . 'UnitTest.inc',
        ]);
    }
}
