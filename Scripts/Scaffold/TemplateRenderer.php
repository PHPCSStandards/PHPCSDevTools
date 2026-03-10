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

/**
 * Class Renderer.
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
     * @var non-empty-string
     */
    private $templateDirectory;

    /**
     *
     * @param FilesystemInterface $filesystem the filesystem to use for reading templates
     */
    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;

        $this->templateDirectory = __DIR__ . \DIRECTORY_SEPARATOR . 'templates';

        if ($filesystem->exists($this->templateDirectory) === false) {
            throw new ScaffolderException('Template directory does not exist: ' . $this->templateDirectory);
        }
    }

    /**
     * Render a template with the provided variables.
     *
     * @param non-empty-string                         $template  the name of the template to render (without the .tpl)
     * @param array<non-empty-string,non-empty-string> $variables the variables to replace in the template,
     *
     * @throws ScaffolderException
     *
     * @return string
     */
    public function render($template, array $variables = [])
    {
        $filePath = $this->templateDirectory . \DIRECTORY_SEPARATOR . $template . '.tpl';

        if ($this->filesystem->exists($filePath) === false) {
            throw new ScaffolderException('Template file does not exist: ' . $filePath);
        }

        $content = $this->filesystem->read($filePath);

        if (empty($variables)) {
            return $content;
        }

        \array_walk($variables, static function ($value, $key) {
            if (\is_string($key) === false) {
                throw new ScaffolderException('Template variable keys must be strings.');
            }
            if (\trim($key) === '') {
                throw new ScaffolderException('Template variable keys must be non-empty strings.');
            }
            if (\is_string($value) === false) {
                throw new ScaffolderException('Template variable values must be strings.');
            }
        });

        return \strtr(
            $content,
            \array_combine(
                \array_map(static function ($key) {
                    return '{ ' . $key . ' }';
                }, \array_keys($variables)),
                $variables
            )
        );
    }
}
