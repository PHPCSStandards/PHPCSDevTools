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

interface TemplateRendererInterface
{

    /**
     * Render a template with the provided variables.
     *
     * @param non-empty-string                         $template  the template content to render
     * @param array<non-empty-string,non-empty-string> $variables an associative array of variables to replace in the template
     *
     * @throws ScaffolderException
     *
     * @return non-empty-string the rendered template content
     */
    public function render($template, array $variables = []);
}
