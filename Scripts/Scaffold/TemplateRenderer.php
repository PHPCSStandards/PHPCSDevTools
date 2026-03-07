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

final class TemplateRenderer implements TemplateRendererInterface
{

    /**
     * @var FileReader
     */
    private $fileReader;

    /**
     * @var non-empty-string
     */
    private $templateDirectory;

    public function __construct(FileReader $fileReader)
    {
        $this->fileReader = $fileReader;

        $this->templateDirectory = __DIR__ . \DIRECTORY_SEPARATOR . 'templates';
    }

    /**
     * @throws ScaffolderException
     */
    public function render($template, array $variables = [])
    {
        $content = $this->fileReader->read($this->templateDirectory . \DIRECTORY_SEPARATOR . $template . '.tpl');

        foreach ($variables as $key => $value) {
            $content = \str_replace('{ ' . $key . ' }', $value, $content);
        }

        return $content;
    }
}
