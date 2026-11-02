<?php

/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests\Scaffold;

use PHPCSDevTools\Scripts\Scaffold\Console\Application;
use PHPCSDevTools\Scripts\Scaffold\Console\ApplicationInterface;
use PHPCSDevTools\Scripts\Scaffold\ContainerInterface;
use PHPCSDevTools\Scripts\Scaffold\DispatcherInterface;
use PHPCSDevTools\Scripts\Scaffold\DotSeparatedSniffInterface;
use PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException;
use PHPCSDevTools\Scripts\Scaffold\FilesystemInterface;
use PHPCSDevTools\Scripts\Scaffold\Listener\ListenerInterface;
use PHPCSDevTools\Scripts\Scaffold\Provider\DirectoryProviderInterface;
use PHPCSDevTools\Scripts\Scaffold\Provider\NamespaceNameProviderInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\DocsPathResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\ResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\SniffFullyQualifiedClassResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\SniffNamespaceResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\SniffPathResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\SniffShortClassResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\UnitTestFullyQualifiedClassResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\UnitTestIncFixedPathResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\UnitTestIncPathResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\UnitTestNamespaceResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\UnitTestPathResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\UnitTestShortClassResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Standard\DirectoryInterface;
use PHPCSDevTools\Scripts\Scaffold\Template\TemplateDirectoryInterface;
use PHPCSDevTools\Scripts\Scaffold\Template\TemplateRenderer;
use PHPCSDevTools\Scripts\Scaffold\Template\TemplateRendererInterface;
use PHPCSDevTools\Scripts\Scaffold\WorkspaceInterface;
use PHPCSDevTools\Scripts\Utils\Writer;
use PHPCSDevTools\Tests\IOTestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Abstract test case for the Scaffold tests.
 *
 * Provides helper methods to create mocks of the various Scaffold components.
 */
abstract class AbstractTestcase extends IOTestCase
{

    /**
     * The path to the project root.
     *
     * @var null|string
     */
    protected $projectRoot;

    /**
     * Build a CLI command for the scaffold executable.
     *
     * @param list<string> $arguments the command arguments to append
     *
     * @return string
     */
    public function buildCliCommand(array $arguments = [])
    {
        return \implode(' ', \array_map('escapeshellarg', \array_merge([
            \PHP_BINARY,
            \implode(\DIRECTORY_SEPARATOR, [$this->getProjectRoot(), 'bin', 'phpcs-scaffold']),
        ], $arguments)));
    }

    /**
     * Create a console application using the default scaffold options.
     *
     * @return Application
     */
    public function createApplication()
    {
        return new Application($this->createMockDispatcher());
    }

    /**
     * Create the mock ContainerInterface.
     *
     * @param null|\Closure(ContainerInterface&MockObject):void $factory optional callback used to configure the mock
     *
     * @return ContainerInterface&MockObject
     */
    public function createMockContainer($factory = null)
    {
        return $this->createMockObject('PHPCSDevTools\\Scripts\\Scaffold\\ContainerInterface', $factory);
    }

    /**
     * Create the mock DirectoryProviderInterface.
     *
     * @param null|\Closure(DirectoryProviderInterface&MockObject):void $factory optional callback used to configure the mock
     *
     * @return DirectoryProviderInterface&MockObject
     */
    public function createMockDirectoryProvider($factory = null)
    {
        return $this->createMockObject(
            'PHPCSDevTools\\Scripts\\Scaffold\\Provider\\DirectoryProviderInterface',
            $factory
        );
    }

    /**
     * Create the mock DispatcherInterface.
     *
     * @param null|\Closure(DispatcherInterface&MockObject):void $factory optional callback used to configure the mock
     *
     * @return DispatcherInterface&MockObject
     */
    public function createMockDispatcher($factory = null)
    {
        return $this->createMockObject('PHPCSDevTools\\Scripts\\Scaffold\\DispatcherInterface', $factory);
    }

    /**
     * Create the mock docs generation listener.
     *
     * @param null|\Closure(ListenerInterface&MockObject):void $factory optional callback used to configure the mock
     *
     * @return ListenerInterface&MockObject
     */
    public function createMockDocsGenerator($factory = null)
    {
        return $this->createMockListener($factory);
    }

    /**
     * Create the mock DocsPathResolverInterface.
     *
     * @param null|\Closure(DocsPathResolverInterface&MockObject):void $factory optional callback used to configure the mock
     *
     * @return DocsPathResolverInterface&MockObject
     */
    public function createMockDocsPathResolver($factory = null)
    {
        return $this->createMockObject(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\DocsPathResolverInterface',
            $factory
        );
    }

    /**
     * Create the mock Filesystem.
     *
     * @param null|\Closure(FilesystemInterface&MockObject):void $factory optional callback used to configure the mock
     *
     * @return FilesystemInterface&MockObject
     */
    public function createMockFilesystem($factory = null)
    {
        return $this->createMockObject('PHPCSDevTools\\Scripts\\Scaffold\\FilesystemInterface', $factory);
    }

    /**
     * Create the mock FullyQualifiedClassResolverInterface.
     *
     * @param null|\Closure(MockObject&ResolverInterface):void $factory optional callback used to configure the mock
     *
     * @return MockObject&ResolverInterface
     */
    public function createMockFullyQualifiedClassResolver($factory = null)
    {
        return $this->createMockObject('PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\ResolverInterface', $factory);
    }

    /**
     * Create the mock listener interface.
     *
     * @param null|\Closure(ListenerInterface&MockObject):void $factory optional callback used to configure the mock
     *
     * @return ListenerInterface&MockObject
     */
    public function createMockGenerator($factory = null)
    {
        return $this->createMockListener($factory);
    }

    /**
     * Create the mock ListenerInterface.
     *
     * @param null|\Closure(ListenerInterface&MockObject):void $factory optional callback used to configure the mock
     *
     * @return ListenerInterface&MockObject
     */
    public function createMockListener($factory = null)
    {
        return $this->createMockObject('PHPCSDevTools\\Scripts\\Scaffold\\Listener\\ListenerInterface', $factory);
    }

    /**
     * Create the mock NamespaceNameProviderInterface.
     *
     * @param null|\Closure(MockObject&NamespaceNameProviderInterface):void $factory optional callback used to configure the mock
     *
     * @return MockObject&NamespaceNameProviderInterface
     */
    public function createMockNamespaceNameProvider($factory = null)
    {
        return $this->createMockObject(
            'PHPCSDevTools\\Scripts\\Scaffold\\Provider\\NamespaceNameProviderInterface',
            $factory
        );
    }

    /**
     * Create the mock NamespaceResolverInterface.
     *
     * @param null|\Closure(MockObject&ResolverInterface):void $factory optional callback used to configure the mock
     *
     * @return MockObject&ResolverInterface
     */
    public function createMockNamespaceResolver($factory = null)
    {
        return $this->createMockObject('PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\ResolverInterface', $factory);
    }

    /**
     * Create a mock object for the supplied interface.
     *
     * @template T of object
     *
     * @param class-string<T>                  $name    the name of the class or interface to mock
     * @param null|\Closure(MockObject&T):void $factory optional callback used to configure the mock
     *
     * @return MockObject&T
     */
    public function createMockObject($name, $factory = null)
    {
        $mockObject = $this->getMockBuilder($name)->getMock();

        if ($factory instanceof \Closure) {
            $factory($mockObject);
        }

        return $mockObject;
    }

    /**
     * Create the mock PathResolverInterface.
     *
     * @param null|\Closure(MockObject&ResolverInterface):void $factory optional callback used to configure the mock
     *
     * @return MockObject&ResolverInterface
     */
    public function createMockPathResolver($factory = null)
    {
        return $this->createMockObject('PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\ResolverInterface', $factory);
    }

    /**
     * Create the mock RendererInterface.
     *
     * @param null|\Closure(MockObject&TemplateRendererInterface):void $factory optional callback used to configure the mock
     *
     * @return MockObject&TemplateRendererInterface
     */
    public function createMockRenderer($factory = null)
    {
        return $this->createMockObject(
            'PHPCSDevTools\\Scripts\\Scaffold\\Template\\TemplateRendererInterface',
            $factory
        );
    }

    /**
     * Create the mock ScaffolderInterface.
     *
     * @param null|\Closure(ApplicationInterface&MockObject):void $factory optional callback used to configure the mock
     *
     * @return ApplicationInterface&MockObject
     */
    public function createMockScaffolder($factory = null)
    {
        return $this->createMockObject('PHPCSDevTools\\Scripts\\Scaffold\\Console\\ApplicationInterface', $factory);
    }

    /**
     * Create the mock ShortClassResolverInterface.
     *
     * @param null|\Closure(MockObject&ResolverInterface):void $factory optional callback used to configure the mock
     *
     * @return MockObject&ResolverInterface
     */
    public function createMockShortClassResolver($factory = null)
    {
        return $this->createMockObject('PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\ResolverInterface', $factory);
    }

    /**
     * Create the mock ShortClassResolverInterface.
     *
     * @param null|\Closure(MockObject&ResolverInterface):void $factory optional callback used to configure the mock
     *
     * @return MockObject&ResolverInterface
     */
    public function createMockShortClassResolverInterface($factory = null)
    {
        return $this->createMockObject('PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\ResolverInterface', $factory);
    }

    /**
     * Create the mock SniffNameInterface.
     *
     * @param null|\Closure(DotSeparatedSniffInterface&MockObject):void $factory optional callback used to configure the mock
     *
     * @return DotSeparatedSniffInterface&MockObject
     */
    public function createMockSniff($factory = null)
    {
        return $this->createMockObject('PHPCSDevTools\\Scripts\\Scaffold\\DotSeparatedSniffInterface', $factory);
    }

    /**
     * Create the mock SniffFullyQualifiedClassResolverInterface.
     *
     * @param null|\Closure(MockObject&SniffFullyQualifiedClassResolverInterface):void $factory optional callback used to configure the mock
     *
     * @return MockObject&SniffFullyQualifiedClassResolverInterface
     */
    public function createMockSniffFullyQualifiedClassResolver($factory = null)
    {
        return $this->createMockObject(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\SniffFullyQualifiedClassResolverInterface',
            $factory
        );
    }

    /**
     * Create the mock sniff generation listener.
     *
     * @param null|\Closure(ListenerInterface&MockObject):void $factory optional callback used to configure the mock
     *
     * @return ListenerInterface&MockObject
     */
    public function createMockSniffGenerator($factory = null)
    {
        return $this->createMockListener($factory);
    }

    /**
     * Create the mock sniff name metadata interface.
     *
     * @param null|\Closure(MockObject):void $factory optional callback used to configure the mock
     *
     * @return MockObject
     */
    public function createMockSniffName2($factory = null)
    {
        return $this->createMockObject('PHPCSDevTools\\Scripts\\Scaffold\\Standard\\SniffInterface', $factory);
    }

    /**
     * Create the mock SniffNamespaceResolverInterface.
     *
     * @param null|\Closure(MockObject&SniffNamespaceResolverInterface):void $factory optional callback used to configure the mock
     *
     * @return MockObject&SniffNamespaceResolverInterface
     */
    public function createMockSniffNamespaceResolver($factory = null)
    {
        return $this->createMockObject(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\SniffNamespaceResolverInterface',
            $factory
        );
    }

    /**
     * Create the mock SniffPathResolverInterface.
     *
     * @param null|\Closure(MockObject&SniffPathResolverInterface):void $factory optional callback used to configure the mock
     *
     * @return MockObject&SniffPathResolverInterface
     */
    public function createMockSniffPathResolver($factory = null)
    {
        return $this->createMockObject(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\SniffPathResolverInterface',
            $factory
        );
    }

    /**
     * Create the mock SniffShortClassResolverInterface.
     *
     * @param null|\Closure(MockObject&SniffShortClassResolverInterface):void $factory optional callback used to configure the mock
     *
     * @return MockObject&SniffShortClassResolverInterface
     */
    public function createMockSniffShortClassResolver($factory = null)
    {
        return $this->createMockObject(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\SniffShortClassResolverInterface',
            $factory
        );
    }

    /**
     * Create the mock StandardDirectoryInterface.
     *
     * @param null|\Closure(DirectoryInterface&MockObject):void $factory optional callback used to configure the mock
     *
     * @return DirectoryInterface&MockObject
     */
    public function createMockStandardDirectory($factory = null)
    {
        return $this->createMockObject('PHPCSDevTools\\Scripts\\Scaffold\\Standard\\DirectoryInterface', $factory);
    }

    /**
     * Create a mock template directory.
     *
     * @return MockObject&TemplateDirectoryInterface
     */
    public function createMockTemplateDirectory()
    {
        return $this->createMockObject(
            'PHPCSDevTools\\Scripts\\Scaffold\\Template\\TemplateDirectoryInterface',
            function ($mock) {
                $path = $this->getProjectRoot() . \DIRECTORY_SEPARATOR . 'templates';

                self::assertDirectoryExists($path);

                $mock->method('toString')->willReturn($path);
            }
        );
    }

    /**
     * Create the mock UnitTestFullyQualifiedClassResolverInterface.
     *
     * @param null|\Closure(MockObject&UnitTestFullyQualifiedClassResolverInterface):void $factory optional callback used to configure the mock
     *
     * @return MockObject&UnitTestFullyQualifiedClassResolverInterface
     */
    public function createMockUnitTestFullyQualifiedClassResolver($factory = null)
    {
        return $this->createMockObject(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestFullyQualifiedClassResolverInterface',
            $factory
        );
    }

    /**
     * Create the mock unit test generation listener.
     *
     * @param null|\Closure(ListenerInterface&MockObject):void $factory optional callback used to configure the mock
     *
     * @return ListenerInterface&MockObject
     */
    public function createMockUnitTestGenerator($factory = null)
    {
        return $this->createMockListener($factory);
    }

    /**
     * Create the mock fixed fixture generation listener.
     *
     * @param null|\Closure(ListenerInterface&MockObject):void $factory optional callback used to configure the mock
     *
     * @return ListenerInterface&MockObject
     */
    public function createMockUnitTestIncFixedGenerator($factory = null)
    {
        return $this->createMockListener($factory);
    }

    /**
     * Create the mock UnitTestIncFixedPathResolverInterface.
     *
     * @param null|\Closure(MockObject&UnitTestIncFixedPathResolverInterface):void $factory optional callback used to configure the mock
     *
     * @return MockObject&UnitTestIncFixedPathResolverInterface
     */
    public function createMockUnitTestIncFixedPathResolver($factory = null)
    {
        return $this->createMockObject(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestIncFixedPathResolverInterface',
            $factory
        );
    }

    /**
     * Create the mock fixture generation listener.
     *
     * @param null|\Closure(ListenerInterface&MockObject):void $factory optional callback used to configure the mock
     *
     * @return ListenerInterface&MockObject
     */
    public function createMockUnitTestIncGenerator($factory = null)
    {
        return $this->createMockListener($factory);
    }

    /**
     * Create the mock UnitTestIncPathResolverInterface.
     *
     * @param null|\Closure(MockObject&UnitTestIncPathResolverInterface):void $factory optional callback used to configure the mock
     *
     * @return MockObject&UnitTestIncPathResolverInterface
     */
    public function createMockUnitTestIncPathResolver($factory = null)
    {
        return $this->createMockObject(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestIncPathResolverInterface',
            $factory
        );
    }

    /**
     * Create the mock UnitTestNamespaceResolverInterface.
     *
     * @param null|\Closure(MockObject&UnitTestNamespaceResolverInterface):void $factory optional callback used to configure the mock
     *
     * @return MockObject&UnitTestNamespaceResolverInterface
     */
    public function createMockUnitTestNamespaceResolver($factory = null)
    {
        return $this->createMockObject(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestNamespaceResolverInterface',
            $factory
        );
    }

    /**
     * Create the mock UnitTestPathResolverInterface.
     *
     * @param null|\Closure(MockObject&UnitTestPathResolverInterface):void $factory optional callback used to configure the mock
     *
     * @return MockObject&UnitTestPathResolverInterface
     */
    public function createMockUnitTestPathResolver($factory = null)
    {
        return $this->createMockObject(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestPathResolverInterface',
            $factory
        );
    }

    /**
     * Create the mock UnitTestShortClassResolverInterface.
     *
     * @param null|\Closure(MockObject&UnitTestShortClassResolverInterface):void $factory optional callback used to configure the mock
     *
     * @return MockObject&UnitTestShortClassResolverInterface
     */
    public function createMockUnitTestShortClassResolver($factory = null)
    {
        return $this->createMockObject(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\UnitTestShortClassResolverInterface',
            $factory
        );
    }

    /**
     * Create the mock WorkspaceInterface.
     *
     * @param null|\Closure(MockObject&WorkspaceInterface):void $factory optional callback used to configure the mock
     *
     * @return MockObject&WorkspaceInterface
     */
    public function createMockWorkspace($factory = null)
    {
        return $this->createMockObject('PHPCSDevTools\\Scripts\\Scaffold\\WorkspaceInterface', $factory);
    }

    /**
     * Create the mock Writer.
     *
     * @param null|\Closure(MockObject&Writer):void $factory optional callback used to configure the mock
     *
     * @return MockObject&Writer
     */
    public function createMockWriter($factory = null)
    {
        return $this->createMockObject('PHPCSDevTools\\Scripts\\Utils\\Writer', $factory);
    }

    /**
     * Create a ruleset.xml file for the given standard directory.
     *
     * @param string $directory the standard directory
     *
     * @return void
     */
    public function createRuleset($directory)
    {
        self::assertDirectoryExists(
            $directory,
            \sprintf('Failed to create ruleset.xml: The directory "%s" does not exist.', $directory)
        );

        \file_put_contents(
            $directory . \DIRECTORY_SEPARATOR . 'ruleset.xml',
            \implode(\PHP_EOL, [
                '<?xml version="1.0"?>',
                '<ruleset name="' . \basename($directory) . '">',
                '</ruleset>',
                '',
            ])
        );
    }

    /**
     * Create a Scaffolder instance with mocked dependencies.
     *
     * @return Application
     */
    public function createScaffolder()
    {
        return $this->createApplication();
    }

    /**
     * Create a temporary workspace directory for CLI integration tests.
     *
     * @return non-empty-string
     */
    public function createTempProjectDirectory()
    {
        $projectDirectory = \sys_get_temp_dir() . \DIRECTORY_SEPARATOR . 'phpcsdevtools-scaffold-' . \md5(\uniqid());

        if (\mkdir($projectDirectory, 0777, true) === false) {
            self::fail('Failed to create the temporary workspace directory.');
        }

        return $projectDirectory;
    }

    /**
     * Create a temporary workspace directory for CLI integration tests.
     *
     * @return string
     */
    public function createTempWorkspace()
    {
        $workspace = \sys_get_temp_dir() . \DIRECTORY_SEPARATOR . 'phpcsdevtools-scaffold-' . \md5(\uniqid());

        if (\mkdir($workspace, 0777, true) === false) {
            self::fail('Failed to create the temporary workspace directory.');
        }

        return $workspace;
    }

    /**
     * Create a renderer for the supplied filesystem double.
     *
     * @param FilesystemInterface        $filesystem        the filesystem mock to inject
     * @param TemplateDirectoryInterface $templateDirectory the template directory mock to inject
     *
     * @throws ScaffolderException
     *
     * @return TemplateRendererInterface
     */
    public function createTemplateRenderer($filesystem = null, $templateDirectory = null)
    {
        if ($filesystem === null) {
            $filesystem = $this->createMockFilesystem();
        }

        if ($templateDirectory === null) {
            $templateDirectory = $this->createMockTemplateDirectory();
        }

        return new TemplateRenderer($filesystem, $templateDirectory);
    }

    /**
     * Get the expected scaffold help output.
     *
     * @return non-empty-string
     */
    public function getHelpText()
    {
        return \implode(\PHP_EOL, [
            'Scaffold a new PHPCS sniff class, along with its unit test, fixtures and documentation files.',
            '',
            'Usage:',
            '  phpcs-scaffold --sniff="Standard.Category.Sniff" --workspace=\\getcwd()',
            '',
            'Example:',
            '  phpcs-scaffold --workspace="./src/Standards" --sniff="Standard.Category.Sniff"',
            '  phpcs-scaffold --workspace="./PHPCSDevTools" --sniff="PHPCSDebug.Debug.TokenList"',
            '  phpcs-scaffold --workspace="./PHPCSExtra" --sniff="Universal.Attributes.BlockOrder"',
            '',
            'Options:',
            '  -h, --help            Print this help.',
            '  -w, --workspace       The workspace where the files and folders will be generated (default: \getcwd()).',
            '  -s, --sniff           The sniff name in the format "Standard.Category.Sniff".',
        ]);
    }

    /**
     * Return the repository root path.
     *
     * @return string
     */
    public function getProjectRoot()
    {
        if (\is_string($this->projectRoot)) {
            return $this->projectRoot;
        }

        return $this->projectRoot = \dirname(\dirname(__DIR__));
    }

    /**
     * Recursively remove a temporary directory created by a test.
     *
     * @param string $path the directory path to remove
     *
     * @return void
     */
    public function removeDirectory($path)
    {
        if (\is_dir($path) === false) {
            return;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $item) {
            if ($item->isDir() === true) {
                \rmdir($item->getPathname());

                continue;
            }

            \unlink($item->getPathname());
        }

        \rmdir($path);
    }
}
