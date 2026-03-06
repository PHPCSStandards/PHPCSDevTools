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


use PHPCSDevTools\Scripts\Scaffold\Resolver\ClassResolver;
use PHPCSDevTools\Scripts\Scaffold\Resolver\NamespaceResolver;
use PHPCSDevTools\Scripts\Scaffold\SniffName;
use PHPCSDevTools\Scripts\Scaffold\TemplateRenderer;

final class SniffGenerator {
    /** @var ClassResolver */
    private $classResolver;
    /** @var NamespaceResolver */
    private $namespaceResolver;
    /** @var TemplateRenderer */
    private $templateRenderer;
    public function __construct(
        ClassResolver $classResolver,
        NamespaceResolver $namespaceResolver,
        TemplateRenderer $templateRenderer
    ) {
        $this->classResolver = $classResolver;
        $this->namespaceResolver = $namespaceResolver;
        $this->templateRenderer = $templateRenderer;
    }

    /** @return non-empty-string */
    public function generate(SniffName $sniffName)
    {
        return $this->templateRenderer->render('sniff.php', [
            'class' => $this->classResolver->resolveSniffClass($sniffName),
            'namespace' => $this->namespaceResolver->resolveSniffNamespace($sniffName),
        ]);
    }
}
