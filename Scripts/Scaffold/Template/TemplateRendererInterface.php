<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Template;

use PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException;

/**
 * Defines template rendering for scaffold files.
 */
interface TemplateRendererInterface
{

    /**
     * Render a template with the provided variables.
     *
     * @param string                  $template  The template name to render
     * @param array<array-key, mixed> $variables An associative array of variables to replace in the template
     *
     * @throws ScaffolderException
     *
     * @return string the rendered template content
     */
    public function render($template, array $variables = []);
}
