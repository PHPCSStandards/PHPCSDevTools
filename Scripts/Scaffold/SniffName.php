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

final class SniffName implements SniffNameInterface
{

    /**
     * @var non-empty-string
     */
    private $category;

    /**
     * @var non-empty-string
     */
    private $name;

    /**
     * @var non-empty-string
     */
    private $rootNamespace;

    /**
     * @var non-empty-string
     */
    private $sniff;

    /**
     * @var non-empty-string
     */
    private $standard;

    private function __construct($name, $rootNamespace, $standard, $category, $sniff)
    {
        if (\is_string($rootNamespace) === false || empty($rootNamespace)) {
            throw new ScaffolderException('Invalid namespace provided. Namespace must be a non-empty string.');
        }

        if (\is_string($standard) === false || empty($standard)) {
            throw new ScaffolderException('Invalid standard provided. Standard must be a non-empty string.');
        }

        if (\is_string($category) === false || empty($category)) {
            throw new ScaffolderException('Invalid category provided. Category must be a non-empty string.');
        }

        if (\is_string($sniff) === false || empty($sniff)) {
            throw new ScaffolderException('Invalid sniff provided. Sniff must be a non-empty string.');
        }

        $this->category      = $category;
        $this->name          = $name;
        $this->rootNamespace = $rootNamespace;
        $this->sniff         = $sniff;
        $this->standard      = $standard;
    }

    /**
     * @return non-empty-string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return non-empty-string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return non-empty-string
     */
    public function getNamespace()
    {
        return $this->rootNamespace;
    }

    /**
     * @return non-empty-string
     */
    public function getSniff()
    {
        return $this->sniff;
    }

    /**
     * @return non-empty-string
     */
    public function getStandard()
    {
        return $this->standard;
    }

    /**
     * @param non-empty-string $name
     *
     * @throws ScaffolderException
     *
     * @return self
     */
    public static function fromString($name)
    {
        if (\is_string($name) === false || empty($name)) {
            throw new ScaffolderException('Invalid sniff name provided. Sniff name must be a non-empty string.');
        }

        $parts = \explode('.', $name, 4);

        if ($parts === false || \count($parts) !== 4) {
            throw new ScaffolderException(
                'Invalid sniff name provided. Sniff name must be in the format "Namespace.Standard.Category.Sniff".'
            );
        }

        return new self($name, $parts[0], $parts[1], $parts[2], $parts[3]);
    }
}
