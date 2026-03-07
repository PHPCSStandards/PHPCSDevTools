<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold;

use PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException;

interface GeneratorInterface
{

    /**
     * Generate the content for a new file.
     *
     * @param SniffNameInterface $sniffName the sniff name to generate for
     * @param WorkspaceInterface $workspace the workspace to generate for
     *
     * @throws ScaffolderException if the generator fails to generate the content
     *
     * @return void
     */
    public function generate(SniffNameInterface $sniffName, WorkspaceInterface $workspace);
}
