<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts\Scaffold\Generator;

use PHPCSDevTools\Scripts\Scaffold\SniffName;
use PHPCSDevTools\Scripts\Scaffold\TemplateRenderer;

final class FixedFixtureGenerator
{
    /** @var TemplateRenderer */
    private $templateRenderer;
    public function __construct(TemplateRenderer $templateRenderer)
    {
        $this->templateRenderer = $templateRenderer;
    }
    public function generate(SniffName $sniffName)
    {
        return $this->templateRenderer->render('fixture.inc.fixed');
    }
}
