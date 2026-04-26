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

use PHPCSDevTools\Scripts\Scaffold\Console\RequestInterface;
use PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException;
use PHPCSDevTools\Scripts\Scaffold\Standard\Category;
use PHPCSDevTools\Scripts\Scaffold\Standard\CategoryInterface;
use PHPCSDevTools\Scripts\Scaffold\Standard\Name;
use PHPCSDevTools\Scripts\Scaffold\Standard\NameInterface;
use PHPCSDevTools\Scripts\Scaffold\Standard\Sniff;
use PHPCSDevTools\Scripts\Scaffold\Standard\SniffInterface;
use PHPCSDevTools\Tests\Scaffold\SniffNameTest;

/**
 * Represents a sniff to be scaffolded.
 *
 * DotSeparatedSniff string with 3 parts: "Standard.Category.Sniff".
 *
 * @see SniffNameTest
 */
final class DotSeparatedSniff implements DotSeparatedSniffInterface
{

    /**
     * The category name of the sniff.
     *
     * @var CategoryInterface
     */
    private $category;

    /**
     * The name of the sniff as a string.
     *
     * @var non-empty-string
     */
    private $name;

    /**
     * The name of the sniff.
     *
     * @var SniffInterface
     */
    private $sniff;

    /**
     * The standard name of the sniff.
     *
     * @var NameInterface
     */
    private $standardName;

    /**
     * Create a new sniff name value object.
     *
     * @param NameInterface     $standardName the standard name for the sniff
     * @param CategoryInterface $category     the category name for the sniff
     * @param SniffInterface    $sniff        the name of the sniff
     */
    public function __construct(NameInterface $standardName, CategoryInterface $category, SniffInterface $sniff)
    {
        $this->name = \sprintf('%s.%s.%s', $standardName->toString(), $category->toString(), $sniff->toString());

        $this->standardName = $standardName;

        $this->category = $category;

        $this->sniff = $sniff;
    }

    /**
     * Get the category name as a string.
     *
     * @return CategoryInterface
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Get the error code as a string.
     *
     * @return SniffInterface
     */
    public function getSniff()
    {
        return $this->sniff;
    }

    /**
     * Get the standard name as a string.
     *
     * @return NameInterface
     */
    public function getStandard()
    {
        return $this->standardName;
    }

    /**
     * Get the name as a string.
     *
     * @return non-empty-string
     */
    public function toString()
    {
        return $this->name;
    }

    /**
     * Create a new Sniff instance from the command line arguments.
     *
     * @param RequestInterface $arguments the command line arguments passed to the script
     *
     * @throws ScaffolderException
     *
     * @return DotSeparatedSniffInterface the Sniff instance created from the command line arguments
     */
    public static function fromRequest(RequestInterface $arguments)
    {
        $validatedArguments = [];

        foreach ($arguments->toArray() as $argument) {
            if ($argument === '--help') {
                // 'Help flag detected. Help message should be printed and script should exit.');
                continue;
            }

            if ($argument === '-h') {
                // 'Help flag detected. Help message should be printed and script should exit.');
                continue;
            }

            $validatedArguments[] = $argument;
        }

        if (isset($validatedArguments[1]) === false) {
            throw new ScaffolderException('Sniff name must be provided as the first argument.');
        }

        return self::fromString($validatedArguments[1]);
    }

    public static function fromString($name)
    {
        if ($name === null) {
            throw new ScaffolderException('Sniff name must be provided.');
        }

        if (\is_string($name) === false) {
            throw new ScaffolderException('Sniff name must be a string.');
        }

        if (\trim($name) === '') {
            throw new ScaffolderException('Sniff name must be a non-empty string.');
        }

        $parts = \explode('.', $name);

        if (\count($parts) === 3) {
            return new self(
                Name::fromString($parts[0]),
                Category::fromString($parts[1]),
                Sniff::fromString($parts[2])
            );
        }

        throw new ScaffolderException(
            \implode(\PHP_EOL, [
                'Invalid sniff name provided.',
                '',
                'Sniff name must be a dot-separated string with 3 parts: "Standard.Category.Sniff".',
            ])
        );
    }
}
