<?php

namespace { unitTestNamespace };

use PHP_CodeSniffer\Tests\Standards\AbstractSniffTestCase;

/**
 * Unit test class for the { sniffName } sniff.
 *
 * @covers { sniffFullyQualifiedClass }
 *
 * @since x.x.x
 */
final class { unitTestShortClass } extends AbstractSniffTestCase
{
    /**
     * Returns the lines where errors should occur.
     *
     * @param string $testFile The name of the file being tested.
     *
     * @return array<int, int> Key is the line number, value is the number of expected errors.
     */
    protected function getErrorList()
    {
        return [];
    }

    /**
     * Returns the lines where warnings should occur.
     *
     * @return array<int, int> Key is the line number, value is the number of expected warnings.
     */
    protected function getWarningList()
    {
        return [];
    }
}
