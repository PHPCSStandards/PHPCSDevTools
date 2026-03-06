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

final class SniffName
{
    private $namespace;
    private $standard;
    private $category;
    private $sniff;

    private function __construct(
        $namespace,
        $standard,
        $category,
        $sniff
    )
    {
        if (!is_string($namespace) || empty($namespace)) {
            throw new ScaffolderException('Invalid namespace provided. Namespace must be a non-empty string.');
        }

        if (!is_string($standard) || empty($standard)) {
            throw new ScaffolderException('Invalid standard provided. Standard must be a non-empty string.');
        }

        if (!is_string($category) || empty($category)) {
            throw new ScaffolderException('Invalid category provided. Category must be a non-empty string.');
        }

        if (!is_string($sniff) || empty($sniff)) {
            throw new ScaffolderException('Invalid sniff provided. Sniff must be a non-empty string.');
        }

        $this->namespace = $namespace;
        $this->standard = $standard;
        $this->category = $category;
        $this->sniff = $sniff;
    }

    /**
     * @param string $name
     *
     * @return self
     *
     * @throws \PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException
     */
    public static function fromString($name)
    {
        if (!is_string($name) || empty($name)) {
            throw new ScaffolderException('Invalid sniff name provided. Sniff name must be a non-empty string.');
        }

        $parts = explode('.', $name, 4);

        if (count($parts) !== 4) {
            throw new ScaffolderException(
                'Invalid sniff name provided. Sniff name must be in the format "Namespace.Standard.Category.Sniff".'
            );
        }

        return new self(
            $parts[0],
            $parts[1],
            $parts[2],
            $parts[3]
        );
    }

    /** @return non-empty-string */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /** @return non-empty-string */
    public function getStandard()
    {
        return $this->standard;
    }

    /** @return non-empty-string */
    public function getCategory()
    {
        return $this->category;
    }

    /** @return non-empty-string */
    public function getSniff()
    {
        return $this->sniff;
    }
}
