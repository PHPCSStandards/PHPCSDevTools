<?php

/**
 * Test fixture.
 *
 * @see \PHPCSDevTools\Tests\DocCodeExamples\XmlDocValidatorTest
 */

namespace Fixtures\DocCodeExamples\CheckCodeExamplesStandard\Sniffs\Examples;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHPCSUtils\Utils\PassedParameters;

class PhpcsUtilsCacheSniff implements Sniff {
    public function register()
    {
        return [T_STRING];
    }

    public function process(File $phpcsFile, $stackPtr)
    {
        $parameter = PassedParameters::getParameter($phpcsFile, $stackPtr, 1);
        if ($parameter['clean'] === '$fail') {
            $phpcsFile->addError('This is a error', $stackPtr, 'TestError');
        }
    }

}
