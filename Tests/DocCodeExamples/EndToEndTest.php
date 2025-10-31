<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests\DocCodeExamples;

use PHPCSDevTools\Tests\IOTestCase;
use PHPUnit\Framework\Attributes\CoversNothing;

/**
 * End-to-end test for the phpcs-check-doc-examples script.
 */
#[CoversNothing]
final class EndToEndTest extends IOTestCase
{
    /**
     * Path to the script.
     *
     * @var string
     */
    const SCRIPT_PATH = 'bin/phpcs-check-doc-examples';

    /**
     * Path to PHPCS.
     *
     * @var string
     */
    const PHPCS_PATH = 'vendor/bin/phpcs';

    /**
     * Stores the original installed_paths. Used to later restore the state after the tests.
     *
     * @var string
     */
    public static $originalInstalledPaths;

    /**
     * Set up for the test class.
     *
     * @beforeClass
     *
     * @return void
     */
    public static function setUpFixturesBeforeClass()
    {
        parent::setUpFixturesBeforeClass();

        // Temporarily install a PHPCS standard to be able to run the tests.
        $phpcsPath       = self::maybeConvertDirectorySeparators(self::PHPCS_PATH);
        $testStandardDir = __DIR__ . '/../Fixtures/DocCodeExamples/CheckCodeExamplesStandard';

        $phpcsConfig = shell_exec($phpcsPath . ' --config-show');
        preg_match('`installed_paths:\s*(.*?)\s*$`m', $phpcsConfig, $matches);
        self::$originalInstalledPaths = isset($matches[1]) ? $matches[1] : '';

        if (strpos(self::$originalInstalledPaths, $testStandardDir) === false) {
            $newPaths = empty(self::$originalInstalledPaths) ? $testStandardDir : self::$originalInstalledPaths . ",$testStandardDir";

            exec($phpcsPath . " --config-set installed_paths $newPaths");
        }
    }

    /**
     * Tear down for the test class.
     *
     * @afterClass
     *
     * @return void
     */
    public static function tearDownFixturesAfterClass()
    {
        parent::tearDownFixturesAfterClass();

        // Restore the original installed_paths
        $phpcsPath = self::maybeConvertDirectorySeparators(self::PHPCS_PATH);
        exec($phpcsPath . " --config-set installed_paths '" . self::$originalInstalledPaths . "'");
    }

    /**
     * Test the basic functionality of the script.
     *
     * @param string $cliArgs          The CLI arguments to pass to the script.
     * @param int    $expectedExitCode The expected exit code of the script.
     * @param string $expectedStdout   The expected stdout.
     * @param string $expectedStderr   The expected stderr.
     *
     * @dataProvider dataScriptBasicExecution
     *
     * @return void
     */
    public function testScriptBasicExecution(string $cliArgs, int $expectedExitCode, string $expectedStdout, string $expectedStderr)
    {
        $scriptPath = self::maybeConvertDirectorySeparators(self::SCRIPT_PATH);

        // Force --no-colors to avoid issues with the tests as colors might be auto enabled or
        // disabled depending on the environment.
        $cliArgs .= ' --no-colors';

        $command = \sprintf(
            'php %s %s',
            $scriptPath,
            $cliArgs
        );

        $result = $this->executeCliCommand($command);

        $this->assertSame($expectedExitCode, $result['exitcode']);

        $this->assertSame($expectedStdout, $result['stdout']);

        if (empty($expectedStderr)) {
            $this->assertStderrContainsOnlyScriptHeader($result['stderr']);
        } else {
            $this->assertMatchesRegularExpression($expectedStderr, $result['stderr']);
        }
    }

    /**
     * Assert that stderr contains only the script header and nothing else.
     *
     * @param string $stderr The stderr output to check.
     *
     * @return void
     */
    protected function assertStderrContainsOnlyScriptHeader(string $stderr)
    {
        $this->assertMatchesRegularExpression(VersionTestUtils::VERSION_HEADER_REGEX, $stderr);
    }

    /**
     * Data provider for testScriptBasicExecution.
     *
     * @return array<string, array<string|int|boolean>>
     */
    public static function dataScriptBasicExecution(): array
    {
        return [
            'Valid XML file'                                                                      => [
                'cliArgs'          => 'Tests/Fixtures/DocCodeExamples/CheckCodeExamplesStandard/Docs/Examples/CorrectExamplesStandard.xml',
                'expectedExitCode' => 0,
                'expectedStdout'   => 'Checked 1 XML documentation file. All code examples are valid.' . PHP_EOL,
                'expectedStderr'   => '',
            ],
            'Directory with valid XML files (invalid files ignored via --ignore-sniffs)'          => [
                'cliArgs'          => 'Tests/Fixtures/DocCodeExamples/CheckCodeExamplesStandard/ --ignore-sniffs=CheckCodeExamplesStandard.Constructor.TestXmlDocValidatorConstructor,CheckCodeExamplesStandard.Examples.IncorrectInvalidExample,CheckCodeExamplesStandard.Examples.IncorrectValidExample,CheckCodeExamplesStandard.Examples.SyntaxErrorExample',
                'expectedExitCode' => 0,
                'expectedStdout'   => 'Checked 7 XML documentation files. All code examples are valid.' . PHP_EOL,
                'expectedStderr'   => '',
            ],
            'Invalid XML file'                                                                    => [
                'cliArgs'          => 'Tests/Fixtures/DocCodeExamples/CheckCodeExamplesStandard/Docs/Examples/IncorrectValidExampleStandard.xml',
                'expectedExitCode' => 1,
                'expectedStdout'   => 'Checked 1 XML documentation file. Found incorrect code examples in 1.' . PHP_EOL,
                'expectedStderr'   => '`Errors found while processing .*?Tests/Fixtures/DocCodeExamples/CheckCodeExamplesStandard/Docs/Examples/IncorrectValidExampleStandard\.xml`',
            ],
            'Directory with valid and invalid XML files (empty file ignored via --ignore-sniffs)' => [
                'cliArgs'          => 'Tests/Fixtures/DocCodeExamples/CheckCodeExamplesStandard/ --ignore-sniffs=CheckCodeExamplesStandard.Constructor.TestXmlDocValidatorConstructor',
                'expectedExitCode' => 1,
                'expectedStdout'   => 'Checked 7 XML documentation files. Found incorrect code examples in 3.' . PHP_EOL,
                'expectedStderr'   => '`Errors found while processing .*?Tests/Fixtures/DocCodeExamples/CheckCodeExamplesStandard/Docs/Examples/IncorrectInvalidExampleStandard\.xml`',
            ],
        ];
    }

    /**
     * If on Windows, convert forward slashes to backslashes so that paths work.
     *
     * @param string $path The path to convert.
     *
     * @return string The converted path.
     */
    public static function maybeConvertDirectorySeparators($path): string
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            return str_replace('/', '\\', $path);
        }

        return $path;
    }
}
