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
use PHPCSDevTools\Scripts\Scaffold\FilesystemInterface;
use PHPCSDevTools\Tests\Scaffold\Template\TemplateRendererTest;

/**
 * Renders scaffold templates using filesystem-backed template files.
 *
 * @see TemplateRendererTest
 */
final class TemplateRenderer implements TemplateRendererInterface
{

    /**
     * The filesystem to use for reading templates.
     *
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * The directory where the templates are stored.
     *
     * @var TemplateDirectoryInterface
     */
    private $templateDirectory;

    /**
     * Create a new template renderer instance.
     *
     * @param FilesystemInterface        $filesystem        The filesystem to use for reading templates
     * @param TemplateDirectoryInterface $templateDirectory The template directory to use for locating templates
     */
    public function __construct(FilesystemInterface $filesystem, TemplateDirectoryInterface $templateDirectory)
    {
        $this->filesystem        = $filesystem;
        $this->templateDirectory = $templateDirectory;
    }

    /**
     * Render a template with the provided variables.
     *
     * @param non-empty-string                          $template
     *                                                             The name of the template to render
     * @param array<non-empty-string, non-empty-string> $variables
     *                                                             The variables to replace in the template
     *
     * @throws ScaffolderException
     *
     * @return string the rendered template contents
     */
    public function render($template, array $variables = [])
    {
        $filePath = $this->templateDirectory->toString() . \DIRECTORY_SEPARATOR . $template . '.tpl';

        if ($this->filesystem->exists($filePath) === false) {
            throw new ScaffolderException('Template file does not exist: ' . $filePath);
        }

        $content = $this->filesystem->read($filePath);

        if ($variables === []) {
            return $content;
        }

        $this->validateVariables($variables);

        return \strtr($content, $this->buildReplacements($variables));
    }

    /**
     * Build placeholder replacements for the template renderer.
     *
     * @param array<non-empty-string, non-empty-string> $variables
     *                                                             The variables to map to placeholders
     *
     * @return array<non-empty-string, non-empty-string>
     */
    private function buildReplacements(array $variables)
    {
        return \array_combine(\array_map([$this, 'formatPlaceholder'], \array_keys($variables)), $variables);
    }

    /**
     * Format a variable key as a template placeholder.
     *
     * @param non-empty-string $key the variable key to format
     *
     * @return non-empty-string
     */
    private function formatPlaceholder(string $key)
    {
        return '{ ' . $key . ' }';
    }

    /**
     * Validate template variables before rendering.
     *
     * @param array<int|string,string> $variables the variables to validate
     *
     * @psalm-assert-if-true array<non-empty-string, non-empty-string> $variables
     *
     * @throws ScaffolderException
     *
     * @return void
     */
    private function validateVariables(array $variables)
    {
        foreach ($variables as $key => $value) {
            if (\is_string($key) === false) {
                throw new ScaffolderException('Template variable keys must be strings.');
            }

            if (\trim($key) === '') {
                throw new ScaffolderException('Template variable keys must be non-empty strings.');
            }

            if (\is_string($value) === false) {
                throw new ScaffolderException('Template variable values must be strings.');
            }
        }
    }
}
