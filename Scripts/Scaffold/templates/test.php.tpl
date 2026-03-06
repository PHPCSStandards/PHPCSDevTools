<?php

namespace { testNamespace };

use PHP_CodeSniffer\Tests\Standards\AbstractSniffTestCase;

/**
 * Unit test class for the { sniffName } sniff.
 *
 * @covers { sniffFullyQualifiedClass }
 *
 * @since x.x.x
 */
final class { testClass } extends AbstractSniffTestCase
{
    /** @inheritDoc */
    protected function getErrorList()
    {
        return [];
    }

    /** @inheritDoc */
    protected function getWarningList()
    {
        return [];
    }
}
