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
use PHPCSDevTools\Scripts\Scaffold\Generator\DocsGeneratorInterface;
use PHPCSDevTools\Scripts\Scaffold\Generator\SniffGeneratorInterface;
use PHPCSDevTools\Scripts\Scaffold\Generator\UnitTestGeneratorInterface;
use PHPCSDevTools\Scripts\Scaffold\Generator\UnitTestIncFixedGeneratorInterface;
use PHPCSDevTools\Scripts\Scaffold\Generator\UnitTestIncGeneratorInterface;
use PHPCSDevTools\Scripts\Utils\Writer;

final class Scaffolder implements ScaffolderInterface
{

    /**
     * @var DocsGeneratorInterface
     */
    private $docsGenerator;

    /**
     * @var SniffGeneratorInterface
     */
    private $sniffGenerator;

    /**
     * @var UnitTestGeneratorInterface
     */
    private $unitTestGenerator;

    /**
     * @var UnitTestIncFixedGeneratorInterface
     */
    private $unitTestIncFixedGenerator;

    /**
     * @var UnitTestIncGeneratorInterface
     */
    private $unitTestIncGenerator;

    /**
     * @var Writer
     */
    private $writer;

    /**
     * @param DocsGeneratorInterface             $docsGenerator
     * @param SniffGeneratorInterface            $sniffGenerator
     * @param UnitTestGeneratorInterface         $unitTestGenerator
     * @param UnitTestIncFixedGeneratorInterface $unitTestIncFixedGenerator
     * @param UnitTestIncGeneratorInterface      $unitTestIncGenerator
     * @param Writer                             $writer
     */
    public function __construct(
        DocsGeneratorInterface $docsGenerator,
        SniffGeneratorInterface $sniffGenerator,
        UnitTestGeneratorInterface $unitTestGenerator,
        UnitTestIncFixedGeneratorInterface $unitTestIncFixedGenerator,
        UnitTestIncGeneratorInterface $unitTestIncGenerator,
        Writer $writer
    ) {
        $this->docsGenerator             = $docsGenerator;
        $this->sniffGenerator            = $sniffGenerator;
        $this->unitTestGenerator         = $unitTestGenerator;
        $this->unitTestIncFixedGenerator = $unitTestIncFixedGenerator;
        $this->unitTestIncGenerator      = $unitTestIncGenerator;
        $this->writer                    = $writer;
    }

    /**
     * Print the help message.
     *
     * @return void
     */
    public function printHelp()
    {
        $this->writer->toStdout(\implode(\PHP_EOL, [
            'Scaffold a new PHPCS sniff class, along with its unit test, fixtures and documentation files.',
            '',
            'Usage:',
            '  phpcs-scaffold Namespace.Standard.Category.Sniff',
            '',
            'Example:',
            '  phpcs-scaffold PHPCSExtra.Universal.DeclareStatements.DeclareStatementsStyle',
            '  phpcs-scaffold MyCompany.MyStandard.MyCategory.MySniff',
            '',
            'Options:',
            '  -h, --help            Print this help.',
        ]));
    }

    /**
     * Scaffold a new sniff, its documentation, and unit tests.
     *
     * @param SniffNameInterface $sniffName the name of the sniff to create, e.g. "Namespace.Standard.Category.Sniff"
     * @param WorkspaceInterface $workspace the path to the workspace where the sniff should be created
     *
     * @throws ScaffolderException
     *
     * @return void
     */
    public function scaffold(SniffNameInterface $sniffName, WorkspaceInterface $workspace)
    {
        $this->writer->toStdout(\sprintf('Scaffolding sniff "%s"%s', $sniffName->getName(), \PHP_EOL . \PHP_EOL));

        $this->docsGenerator->generate($sniffName, $workspace);

        $this->sniffGenerator->generate($sniffName, $workspace);

        $this->unitTestGenerator->generate($sniffName, $workspace);

        $this->unitTestIncFixedGenerator->generate($sniffName, $workspace);

        $this->unitTestIncGenerator->generate($sniffName, $workspace);
    }
}
