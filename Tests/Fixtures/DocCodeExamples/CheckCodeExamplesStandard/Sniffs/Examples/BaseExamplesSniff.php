<?php

/**
 * Test fixture.
 *
 * @see \PHPCSDevTools\Tests\DocCodeExamples\XmlDocValidatorTest
 */

namespace Fixtures\DocCodeExamples\CheckCodeExamplesStandard\Sniffs\Examples;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

abstract class BaseExamplesSniff implements Sniff
{

    public function register()
    {
        return [T_STRING];
    }

    public function process(File $phpcsFile, $stackPtr)
    {
        if ($phpcsFile->getTokens()[$stackPtr]['content'] === 'sniffValidationWillFail') {
            $phpcsFile->addError('This is a error', $stackPtr, 'Error');
        }
    }
}
