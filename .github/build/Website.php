<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools\GHPages
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Build;

use RuntimeException;

/**
 * Prepare the website pages for deploy to GH Pages.
 *
 * {@internal This functionality has a minimum PHP requirement of PHP 7.2.}
 *
 * @internal
 *
 * @phpcs:disable PHPCompatibility.FunctionDeclarations.NewParamTypeDeclarations.stringFound
 * @phpcs:disable PHPCompatibility.FunctionDeclarations.NewReturnTypeDeclarations.intFound
 * @phpcs:disable PHPCompatibility.FunctionDeclarations.NewReturnTypeDeclarations.stringFound
 * @phpcs:disable PHPCompatibility.FunctionDeclarations.NewReturnTypeDeclarations.voidFound
 * @phpcs:disable PHPCompatibility.InitialValue.NewConstantArraysUsingConst.Found
 * @phpcs:disable PHPCompatibility.InitialValue.NewConstantScalarExpressions.constFound
 */
final class Website
{

    /**
     * Path to project root (without trailing slash).
     *
     * @var string
     */
    const PROJECT_ROOT = __DIR__ . '/../..';

    /**
     * Relative path to target directory off project root (without trailing slash).
     *
     * @var string
     */
    const TARGET_DIR = '/deploy';

    /**
     * Files to copy.
     *
     * Source should be the relative path from the project root.
     * Target should be the relative path in the target directory.
     * If target is left empty, the target will be the same as the source.
     *
     * @var array<string => string target>
     */
    const FILES_TO_COPY = [
        'README.md' => 'index.md',
    ];

    /**
     * Frontmatter.
     *
     * @var string
     */
    const FRONTMATTER = '---
---
';

    /**
     * Resolved path to project root (with trailing slash).
     *
     * @var string
     */
    private $realRoot;

    /**
     * Resolved path to target directory (with trailing slash).
     *
     * @var string
     */
    private $realTarget;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        // Check if the target directory exists and if not, create it.
        $targetDir = self::PROJECT_ROOT . self::TARGET_DIR;

        if (@\is_dir($targetDir) === false) {
            if (@\mkdir($targetDir, 0777, true) === false) {
                throw new RuntimeException(\sprintf('Failed to create the %s directory.', $targetDir));
            }
        }

        $realPath = \realpath($targetDir);
        if ($realPath === false) {
            throw new RuntimeException(\sprintf('Failed to find the %s directory.', $targetDir));
        }

        $this->realRoot   = \realpath(self::PROJECT_ROOT) . '/';
        $this->realTarget = $realPath . '/';
    }

    /**
     * Run the transformation.
     *
     * @return int Exit code.
     */
    public function run(): int
    {
        $exitcode = 0;

        try {
            $this->copyFiles();
            $this->transformIndex();
        } catch (RuntimeException $e) {
            echo 'ERROR: ', $e->getMessage(), \PHP_EOL;
            $exitcode = 1;
        }

        return $exitcode;
    }

    /**
     * Copy files to the target directory.
     *
     * @return void
     */
    private function copyFiles(): void
    {
        foreach (self::FILES_TO_COPY as $source => $target) {
            $source = $this->realRoot . $source;
            if (empty($target)) {
                $target = $this->realTarget . $source;
            } else {
                $target = $this->realTarget . $target;
            }

            // Bit round-about way of copying the files, but we need to make sure the target dir exists.
            $contents = $this->getContents($source);
            $this->putContents($target, $contents);
        }
    }

    /**
     * Transform the README to a usable homepage.
     *
     * - Remove the title and subtitle as those would become duplicate.
     * - Remove most of the badges, except for the first three.
     * - Transform those badges into HTML.
     * - Add frontmatter.
     *
     * @return void
     *
     * @throws \RuntimeException When any of the regexes do not yield any results.
     */
    private function transformIndex(): void
    {
        // Read the file.
        $target   = $this->realTarget . '/index.md';
        $contents = $this->getContents($target);

        // Grab the start of the document.
        $matched = \preg_match('`^(.+)\* \[Installation\]`s', $contents, $matches);
        if ($matched !== 1) {
            throw new RuntimeException('Failed to match start of document. Adjust the regex');
        }

        $startOfDoc = $matches[1];

        // Grab the first few badges from the start of the document.
        $matched = \preg_match(
            '`((?:\[!\[[^\]]+\]\([^\)]+\)\]\([^\)]+\)[\n\r]+)+):construction:`',
            $startOfDoc,
            $matches
        );
        if ($matched !== 1) {
            throw new RuntimeException('Failed to match badges. Adjust the regex');
        }

        $badges = \explode("\n", $matches[1]);
        $badges = \array_filter($badges);
        $badges = \array_map([$this, 'mdBadgeToHtml'], $badges);
        $badges = \implode("\n    ", $badges);

        $replacement = \sprintf(
            '%s

<div id="badges" aria-hidden="true">

%s

</div>

',
            self::FRONTMATTER,
            '    ' . $badges
        );

        $contents = \str_replace($startOfDoc, $replacement, $contents);

        $this->putContents($target, $contents);
    }

    /**
     * Transform markdown badges into HTML badges.
     *
     * Jekyll runs into trouble doing this when we also want to keep the wrapper div with aria-hidden="true".
     *
     * @param string $mdBadge Markdown badge code.
     *
     * @return string
     */
    private function mdBadgeToHtml(string $mdBadge): string
    {
        $mdBadge = \trim($mdBadge);

        $matched = \preg_match(
            '`^\[!\[(?<alt>[^\]]+)\]\((?<imgurl>[^\)]+)\)\]\((?<href>[^\)]+)\)$`',
            $mdBadge,
            $matches
        );
        if ($matched !== 1) {
            throw new RuntimeException(\sprintf('Failed to parse the badge. Adjust the regex. Received: %s', $mdBadge));
        }

        return \sprintf(
            '<a href="%s"><img src="%s" alt="%s" class="badge"></a>',
            $matches['href'],
            $matches['imgurl'],
            $matches['alt']
        );
    }

    /**
     * Retrieve the contents of a file.
     *
     * @param string $source Path to the source file.
     *
     * @return string
     *
     * @throws \RuntimeException When the contents of the file could not be retrieved.
     */
    private function getContents(string $source): string
    {
        $contents = \file_get_contents($source);
        if (!$contents) {
            throw new RuntimeException(\sprintf('Failed to read doc file: %s', $source));
        }

        return $contents;
    }

    /**
     * Write a string to a file.
     *
     * @param string $target   Path to the target file.
     * @param string $contents File contents to write.
     *
     * @return void
     *
     * @throws \RuntimeException When the target directory could not be created.
     * @throws \RuntimeException When the file could not be written to the target directory.
     */
    private function putContents(string $target, string $contents): void
    {
        // Check if the target directory exists and if not, create it.
        $targetDir = \dirname($target);

        if (@\is_dir($targetDir) === false) {
            if (@\mkdir($targetDir, 0777, true) === false) {
                throw new RuntimeException(\sprintf('Failed to create the %s directory.', $targetDir));
            }
        }

        // Make sure the file always ends on a new line.
        $contents = \rtrim($contents) . "\n";
        if (\file_put_contents($target, $contents) === false) {
            throw new RuntimeException(\sprintf('Failed to write to target location: %s', $target));
        }
    }
}
