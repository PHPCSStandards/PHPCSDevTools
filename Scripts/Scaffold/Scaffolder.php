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

use PHPCSDevTools\Scripts\Scaffold\Generator\DocumentationGenerator;
use PHPCSDevTools\Scripts\Scaffold\Generator\FixedFixtureGenerator;
use PHPCSDevTools\Scripts\Scaffold\Generator\FixtureGenerator;
use PHPCSDevTools\Scripts\Scaffold\Generator\SniffGenerator;
use PHPCSDevTools\Scripts\Scaffold\Generator\UnitTestGenerator;
use PHPCSDevTools\Scripts\Scaffold\Resolver\NamespaceResolver;
use PHPCSDevTools\Scripts\Scaffold\Resolver\PathResolver;
use PHPCSDevTools\Scripts\Utils\Writer;

final class Scaffolder
{
    /**
     * Search & replace values to convert a sniff file path into a docs file path.
     *
     * Keys are the strings to search for, values the replacement values.
     *
     * @var array<string, string>
     */
    private $sniffToDoc = [
        '/Sniffs/'  => '/Docs/',
        'Sniff.php' => 'Standard.xml',
    ];

    /**
     * Search & replace values to convert a sniff file path into a unit test file path.
     *
     * Keys are the strings to search for, values the replacement values.
     *
     * @var array<string, string>
     */
    private $sniffToUnitTest = [
        '/Sniffs/' => '/Tests/',
        'Sniff.'   => 'UnitTest.',
    ];

    /**
     * Possible test case file extensions.
     *
     * @var array<string>
     */
    private $testCaseExtensions = [
        '.inc',
        '.css',
        '.js',
        '.1.inc',
        '.1.css',
        '.1.js',
    ];

    /* @var DocumentationGenerator */
    private $documentationGenerator;
    /** @var FileCreator */
    private $fileCreator;
    /** @var FixtureGenerator */
    private $fixtureGenerator;
    /** @var FixedFixtureGenerator */
    private $fixedFixtureGenerator;
    /** @var NamespaceResolver */
    private $namespaceResolver;
    /* @var PathResolver */
    private $pathResolver;
    /* @var SniffGenerator */
    private $sniffGenerator;
    /* @var UnitTestGenerator */
    private $unitTestGenerator;
    /** @var Writer */
    private $writer;

    /**
     * Scaffolder constructor.
     *
     * @param DocumentationGenerator $documentationGenerator
     * @param FileCreator $fileCreator
     * @param FixedFixtureGenerator $fixedFixtureGenerator
     * @param FixtureGenerator $fixtureGenerator
     * @param NamespaceResolver $namespaceResolver
     * @param PathResolver $pathResolver
     * @param SniffGenerator $sniffGenerator
     * @param UnitTestGenerator $unitTestGenerator
     * @param Writer $writer
     */
    public function __construct(
        DocumentationGenerator $documentationGenerator,
        FileCreator $fileCreator,
        FixedFixtureGenerator $fixedFixtureGenerator,
        FixtureGenerator $fixtureGenerator,
        NamespaceResolver $namespaceResolver,
        PathResolver $pathResolver,
        SniffGenerator $sniffGenerator,
        UnitTestGenerator $unitTestGenerator,
        Writer $writer
    )    {
        $this->documentationGenerator = $documentationGenerator;
        $this->fileCreator = $fileCreator;
        $this->fixedFixtureGenerator = $fixedFixtureGenerator;
        $this->fixtureGenerator = $fixtureGenerator;
        $this->namespaceResolver = $namespaceResolver;
        $this->pathResolver = $pathResolver;
        $this->sniffGenerator = $sniffGenerator;
        $this->unitTestGenerator = $unitTestGenerator;
        $this->writer = $writer;
    }

    /**
     * Scaffold a new sniff, its documentation, and unit tests.
     *
     * @param string $workspace The path to the workspace where the sniff should be created.
     * @param string $name The name of the sniff to create, in the format "Namespace.Standard.Category.Sniff".
     *
     * @return void
     *
     * @throws \PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException
     */
    public function scaffold($workspace, $name)
    {
        $workspace = Workspace::fromString($workspace);
        $sniffName = SniffName::fromString($name);

        $this->createDocumentation($workspace, $sniffName);
        $this->createSniff($workspace, $sniffName);
        $this->createUnitTest($workspace, $sniffName);
        $this->createUnitTestInc($workspace, $sniffName);
        $this->createUnitTestIncFixed($workspace, $sniffName);
    }

    private function createDocumentation(Workspace $workspace, SniffName $sniffName)
    {
        $path = $this->pathResolver->resolveDocumentationPath($workspace, $sniffName);
        if (file_exists($path)) {
            $this->writer->toStderr('File already exists: ' . $path . PHP_EOL);

            return;
        }

        $this->writer->toStdout('Creating file: ' . $path . PHP_EOL);

        $contents = $this->documentationGenerator->generate($sniffName);

        $this->fileCreator->create($path, $contents);

        $this->writer->toStdout('Created file: ' . $path . PHP_EOL);

    }

    /**
     * @throws \PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException
     */
    private function createSniff(Workspace $workspace, SniffName $sniffName)
    {
        $path = $this->pathResolver->resolveSniffPath($workspace, $sniffName);
        if (file_exists($path)) {
            $this->writer->toStderr('File already exists: ' . $path . PHP_EOL);

            return;
        }

        $this->writer->toStdout('Creating file: ' . $path . PHP_EOL);

        $contents = $this->sniffGenerator->generate($sniffName);

        $this->fileCreator->create($path, $contents);

        $this->writer->toStdout('Created file: ' . $path . PHP_EOL);
    }

    /**
     * @throws \PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException
     */
    private function createUnitTest(Workspace $workspace, SniffName $sniffName)
    {
        $path = $this->pathResolver->resolveUnitTestPath($workspace, $sniffName);
        if (file_exists($path)) {
            $this->writer->toStderr('File already exists: ' . $path . PHP_EOL);

            return;
        }

        $this->writer->toStdout('Creating file: ' . $path . PHP_EOL);

        $contents = $this->unitTestGenerator->generate($sniffName);

        $this->fileCreator->create($path, $contents);

        $this->writer->toStdout('Created file: ' . $path . PHP_EOL);
    }

    /**
     * @throws \PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException
     */
    private function createUnitTestInc(Workspace $workspace, SniffName $sniffName)
    {
        $path = $this->pathResolver->resolveUnitTestIncPath($workspace, $sniffName);
        if (file_exists($path)) {
            $this->writer->toStderr('File already exists: ' . $path . PHP_EOL);

            return;
        }

        $this->writer->toStdout('Creating file: ' . $path . PHP_EOL);

        $contents = $this->fixtureGenerator->generate($sniffName);

        $this->fileCreator->create($path, $contents);

        $this->writer->toStdout('Created file: ' . $path . PHP_EOL);
    }

    /**
     * @throws \PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException
     */
    private function createUnitTestIncFixed(Workspace $workspace, SniffName $sniffName)
    {
        $path = $this->pathResolver->resolveUnitTestIncFixedPath($workspace, $sniffName);
        if (file_exists($path)) {
            $this->writer->toStderr('File already exists: ' . $path . PHP_EOL);

            return;
        }

        $this->writer->toStdout('Creating file: ' . $path . PHP_EOL);

        $contents = $this->fixedFixtureGenerator->generate($sniffName);

        $this->fileCreator->create($path, $contents);

        $this->writer->toStdout('Created file: ' . $path . PHP_EOL);
    }

    /**
     * Print the help message
     *
     * @return void
     */
    public function printHelp()
    {
        $this->writer->toStdout(implode(PHP_EOL, [
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
}
