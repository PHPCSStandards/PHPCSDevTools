<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Scripts;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

/**
 * Retrieve a filtered file list.
 *
 * @since 1.0.0
 */
class FileList
{

    /**
     * Base regex to use if no filter regex is provided.
     *
     * Matches based on:
     * - File path starts with the project root (replacement done in constructor).
     * - Don't match .git/ files.
     * - Don't match dot files, i.e. "." or "..".
     * - Don't match backup files.
     * - Match everything else in a case-insensitive manner.
     *
     * @var string
     */
    const BASE_REGEX = '`^%s(?!\.git/)(?!(.*/)?\.+$)(?!.*\.(bak|orig)).*$`Di';

    /**
     * The path to the project root directory.
     *
     * @var string
     */
    protected $rootPath;

    /**
     * Regex iterator.
     *
     * @var \RegexIterator
     */
    protected $fileIterator;

    /**
     * Constructor.
     *
     * @param string $directory The directory to examine.
     * @param string $rootPath  Path to the project root.
     * @param string $filter    PCRE regular expression to filter the file list with.
     */
    public function __construct($directory, $rootPath = '', $filter = '')
    {
        $this->rootPath = $rootPath;

        $directory = new RecursiveDirectoryIterator(
            $directory,
            FilesystemIterator::UNIX_PATHS | FilesystemIterator::SKIP_DOTS
        );

        $flattened = new RecursiveIteratorIterator(
            $directory,
            RecursiveIteratorIterator::LEAVES_ONLY,
            RecursiveIteratorIterator::CATCH_GET_CHILD
        );

        if ($filter === '') {
            $filter = \sprintf(self::BASE_REGEX, \preg_quote($this->rootPath . \DIRECTORY_SEPARATOR, '`'));
        }

        $this->fileIterator = new RegexIterator($flattened, $filter);

        return $this;
    }

    /**
     * Retrieve the filtered file list iterator.
     *
     * @return \RegexIterator
     */
    public function getIterator()
    {
        return $this->fileIterator;
    }

    /**
     * Retrieve the filtered file list as an array.
     *
     * @return array
     */
    public function getList()
    {
        $fileList = [];

        foreach ($this->fileIterator as $file) {
            $fileList[] = \str_replace($this->rootPath, '', $file);
        }

        return $fileList;
    }
}
