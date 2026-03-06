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
use PHPCSDevTools\Scripts\Scaffold\Workspace;

final class PathResolver
{
    /**
     * @return non-empty-string
     */
    public function resolveDocumentationPath(Workspace $workspace, SniffName $name)
    {
        return sprintf(
            '%s/%s/Docs/%s/%sStandard.xml',
            $workspace->getPath(),
            $name->getStandard(),
            $name->getCategory(),
            $name->getSniff()
        );
    }

    /**
     * @return non-empty-string
     */
    public function resolveSniffPath(Workspace $workspace, SniffName $name)
    {
        return sprintf(
            '%s/%s/Sniffs/%s/%sSniff.php',
            $workspace->getPath(),
            $name->getStandard(),
            $name->getCategory(),
            $name->getSniff()
        );
    }

    /**
     * @return non-empty-string
     */
    public function resolveUnitTestPath(Workspace $workspace, SniffName $name)
    {
        return sprintf(
            '%s/%s/Tests/%s/%sUnitTest.php',
            $workspace->getPath(),
            $name->getStandard(),
            $name->getCategory(),
            $name->getSniff()
        );
    }

    /**
     * @return non-empty-string
     */
    public function resolveUnitTestIncPath(Workspace $workspace, SniffName $name)
    {
        return sprintf(
            '%s/%s/Tests/%s/%sUnitTest.inc',
            $workspace->getPath(),
            $name->getStandard(),
            $name->getCategory(),
            $name->getSniff()
        );
    }

    /**
     * @return non-empty-string
     */
    public function resolveUnitTestIncFixedPath(Workspace $workspace, SniffName $name)
    {
        return sprintf(
            '%s/%s/Tests/%s/%sUnitTest.inc.fixed',
            $workspace->getPath(),
            $name->getStandard(),
            $name->getCategory(),
            $name->getSniff()
        );
    }
}
