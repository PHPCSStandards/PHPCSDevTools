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

    /**
     * @param non-empty-string $name full sniff name in the format "Namespace.Standard.Category.Sniff"
     */
    public function __construct($name)
    {
        if (\is_string($name) === false) {
            throw new ScaffolderException('Sniff name must be a string.');
        }

        if (\trim($name) === '') {
            throw new ScaffolderException('Sniff name must be a non-empty string.');
        }

        $parts = \explode('.', $name, 4);

        if (\count($parts) !== 4) {
            throw new ScaffolderException(
                'Sniff name must be a dot-separated string with 4 parts: "Namespace.Standard.Category.Sniff".'
            );
        }

        $rootNamespace = $parts[0];

        if (\trim($rootNamespace) === '') {
            throw new ScaffolderException('Namespace must be a non-empty string.');
        }

        $standard = $parts[1];

        if (\trim($standard) === '') {
            throw new ScaffolderException('Standard must be a non-empty string.');
        }

        $category = $parts[2];

        if (\trim($category) === '') {
            throw new ScaffolderException('Category must be a non-empty string.');
        }

        $sniff = $parts[3];

        if (\trim($sniff) === '') {
            throw new ScaffolderException('Sniff must be a non-empty string.');
        }

        if (\strpos($sniff, '.') !== false) {
            throw new ScaffolderException('Invalid sniff provided. Sniff must not contain a dot.');
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
}
