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

final class UnitTestGenerator
{
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
    public function generate(SniffName $sniffName)
    {
        $sniffClass = $this->classResolver->resolveSniffClass($sniffName);
        $sniffNamespace = $this->namespaceResolver->resolveSniffNamespace($sniffName);

        return $this->templateRenderer->render('test.php', [
            'sniffName' => $sniffName->getSniff(),
            'sniffClass' => $sniffClass,
            'sniffNamespace' => $sniffNamespace,
            'testClass' => $this->classResolver->resolveUnitTestClass($sniffName),
            'testNamespace' => $this->namespaceResolver->resolveUnitTestNamespace($sniffName),
            'sniffFullyQualifiedClass' => $sniffNamespace . '\\' . $sniffClass,
        ]);
    }
}
