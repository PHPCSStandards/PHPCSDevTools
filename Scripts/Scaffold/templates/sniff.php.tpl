<?php

namespace { namespace };

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * description
 *
 * @since x.x.x
 */
final class { class } implements Sniff
{
    /**
     * Name of the metric.
     *
     * @since x.x.x
     *
     * @var string
     */
    const METRIC_NAME = 'name';

    /**
    * Returns an array of tokens this test wants to listen for.
    *
    * @since x.x.x
    *
    * @return array<int|string>
    */
    public function register()
    {
        return [];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since x.x.x
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token
     *                                               in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        // $filename = $phpcsFile->getFilename();
        // $tokens = $phpcsFile->getTokens();
    }
}
