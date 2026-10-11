<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests\Scaffold\Metadata;

use PHPCSDevTools\Scripts\Scaffold\Filesystem;
use PHPCSDevTools\Scripts\Scaffold\Ruleset\RulesetParser;
use PHPCSDevTools\Scripts\Scaffold\Ruleset\RulesetReader;
use PHPCSDevTools\Scripts\Scaffold\Standard\Ruleset;
use PHPCSDevTools\Tests\Scaffold\AbstractTestcase;

/**
 * Test ruleset reading and parsing.
 *
 * @covers \PHPCSDevTools\Scripts\Scaffold\Ruleset\RulesetParser
 * @covers \PHPCSDevTools\Scripts\Scaffold\Ruleset\RulesetReader
 *
 * @uses \PHPCSDevTools\Scripts\Scaffold\Filesystem
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Directory
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Name
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\NamespaceName
 * @uses \PHPCSDevTools\Scripts\Scaffold\Standard\Ruleset
 * @uses \PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException
 */
final class StandardMetadataProviderTest extends AbstractTestcase
{

    /**
     * Verify rulesets without a namespace fall back to the standard name.
     *
     * @return void
     */
    public function testRulesetParserFallsBackToTheStandardNameWhenNamespaceIsMissing()
    {
        $standardDirectory = $this->createTempWorkspace();
        $rulesetPath       = $standardDirectory . \DIRECTORY_SEPARATOR . 'ruleset.xml';

        try {
            \file_put_contents(
                $rulesetPath,
                \implode(\PHP_EOL, ['<?xml version="1.0"?>', '<ruleset name="DemoStandard">', '</ruleset>', ''])
            );

            $rulesetParser = new RulesetParser(new RulesetReader(new Filesystem()));
            $result        = $rulesetParser->parse(Ruleset::fromString($rulesetPath));

            self::assertSame('DemoStandard', $result->getNamespaceName()->toString());
        } catch (\Exception $exception) {
        }

        $this->removeDirectory($standardDirectory);
    }

    /**
     * Verify rulesets with an explicit namespace are parsed into standard metadata.
     *
     * @return void
     */
    public function testRulesetParserReturnsMetadataForARulesetWithAnExplicitNamespace()
    {
        $standardDirectory = $this->createTempWorkspace();
        $rulesetPath       = $standardDirectory . \DIRECTORY_SEPARATOR . 'ruleset.xml';

        try {
            \file_put_contents(
                $rulesetPath,
                \implode(\PHP_EOL, [
                    '<?xml version="1.0"?>',
                    '<ruleset name="DemoStandard" namespace="Vendor\\DemoStandard">',
                    '</ruleset>',
                    '',
                ])
            );

            $rulesetParser = new RulesetParser(new RulesetReader(new Filesystem()));
            $result        = $rulesetParser->parse(Ruleset::fromString($rulesetPath));

            self::assertSame('DemoStandard', $result->getName()->toString());
            self::assertSame('Vendor\\DemoStandard', $result->getNamespaceName()->toString());
            self::assertSame($standardDirectory, $result->getDirectory()->toString());
            self::assertSame($rulesetPath, $result->getRuleset()->toString());
        } catch (\Exception $exception) {
        }

        $this->removeDirectory($standardDirectory);
    }

    /**
     * Verify invalid XML content is rejected.
     *
     * @return void
     */
    public function testRulesetParserThrowsForInvalidXml()
    {
        $standardDirectory = $this->createTempWorkspace();
        $rulesetPath       = $standardDirectory . \DIRECTORY_SEPARATOR . 'ruleset.xml';

        try {
            $this->expectException('PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException');
            $this->expectExceptionMessage('Failed to parse ruleset file "' . $rulesetPath . '".');
            \file_put_contents($rulesetPath, '<ruleset');

            $rulesetParser = new RulesetParser(new RulesetReader(new Filesystem()));
            $rulesetParser->parse(Ruleset::fromString($rulesetPath));
        } catch (\Exception $exception) {
            $this->removeDirectory($standardDirectory);

            throw $exception;
        }

        $this->removeDirectory($standardDirectory);
    }

    /**
     * Verify rulesets without a name attribute are rejected.
     *
     * @return void
     */
    public function testRulesetParserThrowsWhenTheRulesetNameIsMissing()
    {
        $standardDirectory = $this->createTempWorkspace();
        $rulesetPath       = $standardDirectory . \DIRECTORY_SEPARATOR . 'ruleset.xml';

        try {
            \file_put_contents(
                $rulesetPath,
                \implode(\PHP_EOL, [
                    '<?xml version="1.0"?>',
                    '<ruleset namespace="Vendor\\DemoStandard">',
                    '</ruleset>',
                    '',
                ])
            );

            $this->expectException('PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException');
            $this->expectExceptionMessage(
                'Failed to determine standard name from ruleset file "' . $rulesetPath . '".'
            );

            $rulesetParser = new RulesetParser(new RulesetReader(new Filesystem()));
            $rulesetParser->parse(Ruleset::fromString($rulesetPath));
        } catch (\Exception $exception) {
            $this->removeDirectory($standardDirectory);

            throw $exception;
        }

        $this->removeDirectory($standardDirectory);
    }

    /**
     * Verify the ruleset reader delegates to the filesystem.
     *
     * @return void
     */
    public function testRulesetReaderDelegatesToTheFilesystem()
    {
        $rulesetPath = '/tmp/ruleset.xml';
        $ruleset     = Ruleset::fromString($rulesetPath);
        $filesystem  = $this->createMockFilesystem(function ($mock) use ($rulesetPath) {
            $mock->expects(self::once())
                ->method('read')
                ->with($rulesetPath)
                ->willReturn('<ruleset name="DemoStandard"/>');
        });

        $rulesetReader = new RulesetReader($filesystem);

        self::assertSame('<ruleset name="DemoStandard"/>', $rulesetReader->read($ruleset));
    }
}
