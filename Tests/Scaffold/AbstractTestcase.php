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

use PHPCSDevTools\Scripts\Scaffold\Exception\ScaffolderException;
use PHPCSDevTools\Scripts\Scaffold\FilesystemInterface;
use PHPCSDevTools\Scripts\Scaffold\Generator\DocsGeneratorInterface;
use PHPCSDevTools\Scripts\Scaffold\Generator\SniffGeneratorInterface;
use PHPCSDevTools\Scripts\Scaffold\Generator\UnitTestGeneratorInterface;
use PHPCSDevTools\Scripts\Scaffold\Generator\UnitTestIncFixedGeneratorInterface;
use PHPCSDevTools\Scripts\Scaffold\Generator\UnitTestIncGeneratorInterface;
use PHPCSDevTools\Scripts\Scaffold\GeneratorInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\FullyQualifiedClassResolver\SniffFullyQualifiedClassResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\FullyQualifiedClassResolver\UnitTestFullyQualifiedClassResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\FullyQualifiedClassResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\NamespaceResolver\SniffNamespaceResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\NamespaceResolver\UnitTestNamespaceResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\NamespaceResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\PathResolver\DocsPathResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\PathResolver\SniffPathResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\PathResolver\UnitTestIncFixedPathResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\PathResolver\UnitTestIncPathResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\PathResolver\UnitTestPathResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\PathResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\ShortClassResolver\SniffShortClassResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\ShortClassResolver\UnitTestShortClassResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\Resolver\ShortClassResolverInterface;
use PHPCSDevTools\Scripts\Scaffold\ScaffolderInterface;
use PHPCSDevTools\Scripts\Scaffold\SniffNameInterface;
use PHPCSDevTools\Scripts\Scaffold\TemplateRenderer;
use PHPCSDevTools\Scripts\Scaffold\TemplateRendererInterface;
use PHPCSDevTools\Scripts\Scaffold\WorkspaceInterface;
use PHPCSDevTools\Scripts\Utils\Writer;
use PHPUnit\Framework\MockObject\MockObject;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

/**
 * Abstract test case for the Scaffold tests.
 *
 * Provides helper methods to create mocks of the various Scaffold components.
 */
abstract class AbstractTestcase extends TestCase
{

    /**
     * Create the mock DocsGeneratorInterface.
     *
     * @param null|\Closure(DocsGeneratorInterface&MockObject):void $factory
     *
     * @return DocsGeneratorInterface&MockObject
     */
    public function createMockDocsGenerator($factory = null)
    {
        $mockObject = $this->getMockBuilder('PHPCSDevTools\\Scripts\\Scaffold\\Generator\\DocsGeneratorInterface')->getMock();

        if ($factory instanceof \Closure) {
            $factory($mockObject);
        }

        return $mockObject;
    }

    /**
     * Create the mock DocsPathResolverInterface.
     *
     * @param null|\Closure(DocsPathResolverInterface&MockObject):void $factory
     *
     * @return DocsPathResolverInterface&MockObject
     */
    public function createMockDocsPathResolver($factory = null)
    {
        $mockObject = $this->createMock(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\PathResolver\\DocsPathResolverInterface'
        );

        if ($factory instanceof \Closure) {
            $factory($mockObject);
        }

        return $mockObject;
    }

    /**
     * Create the mock Filesystem.
     *
     * @param null|\Closure(FilesystemInterface&MockObject):void $factory
     *
     * @return FilesystemInterface&MockObject
     */
    public function createMockFilesystem($factory = null)
    {
        $mockObject = $this->getMockBuilder('PHPCSDevTools\\Scripts\\Scaffold\\FilesystemInterface')->getMock();

        if ($factory instanceof \Closure) {
            $factory($mockObject);
        }

        return $mockObject;
    }

    /**
     * Create the mock FullyQualifiedClassResolverInterface.
     *
     * @param null|\Closure(FullyQualifiedClassResolverInterface&MockObject):void $factory
     *
     * @return FullyQualifiedClassResolverInterface&MockObject
     */
    public function createMockFullyQualifiedClassResolver($factory = null)
    {
        $mockObject = $this->createMock(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\FullyQualifiedClassResolverInterface'
        );

        if ($factory instanceof \Closure) {
            $factory($mockObject);
        }

        return $mockObject;
    }

    /**
     * Create the mock GeneratorInterface.
     *
     * @param null|\Closure(GeneratorInterface&MockObject):void $factory
     *
     * @return GeneratorInterface&MockObject
     */
    public function createMockGenerator($factory = null)
    {
        $mockObject = $this->getMockBuilder('PHPCSDevTools\\Scripts\\Scaffold\\GeneratorInterface')->getMock();

        if ($factory instanceof \Closure) {
            $factory($mockObject);
        }

        return $mockObject;
    }

    /**
     * Create the mock NamespaceResolverInterface.
     *
     * @param null|\Closure(MockObject&NamespaceResolverInterface):void $factory
     *
     * @return MockObject&NamespaceResolverInterface
     */
    public function createMockNamespaceResolver($factory = null)
    {
        $mockObject = $this->getMockBuilder('PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\NamespaceResolverInterface')->getMock();

        if ($factory instanceof \Closure) {
            $factory($mockObject);
        }

        return $mockObject;
    }

    /**
     * Create the mock PathResolverInterface.
     *
     * @param null|\Closure(MockObject&PathResolverInterface):void $factory
     *
     * @return MockObject&PathResolverInterface
     */
    public function createMockPathResolver($factory = null)
    {
        $mockObject = $this->getMockBuilder('PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\PathResolverInterface')->getMock();

        if ($factory instanceof \Closure) {
            $factory($mockObject);
        }

        return $mockObject;
    }

    /**
     * Create the mock RendererInterface.
     *
     * @param null|\Closure(MockObject&TemplateRendererInterface):void $factory
     *
     * @return MockObject&TemplateRendererInterface
     */
    public function createMockRenderer($factory = null)
    {
        $mockObject = $this->getMockBuilder('PHPCSDevTools\\Scripts\\Scaffold\\TemplateRendererInterface')->getMock();

        if ($factory instanceof \Closure) {
            $factory($mockObject);
        }

        return $mockObject;
    }

    /**
     * Create the mock ScaffolderInterface.
     *
     * @param null|\Closure(MockObject&ScaffolderInterface):void $factory
     *
     * @return MockObject&ScaffolderInterface
     */
    public function createMockScaffolder($factory = null)
    {
        $mockObject = $this->getMockBuilder('PHPCSDevTools\\Scripts\\Scaffold\\ScaffolderInterface')->getMock();

        if ($factory instanceof \Closure) {
            $factory($mockObject);
        }

        return $mockObject;
    }

    /**
     * Create the mock ShortClassResolverInterface.
     *
     * @param null|\Closure(MockObject&ShortClassResolverInterface):void $factory
     *
     * @return MockObject&ShortClassResolverInterface
     */
    public function createMockShortClassResolver($factory = null)
    {
        $mockObject = $this->getMockBuilder('PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\ShortClassResolverInterface')->getMock();

        if ($factory instanceof \Closure) {
            $factory($mockObject);
        }

        return $mockObject;
    }

    /**
     * Create the mock ShortClassResolverInterface.
     *
     * @param null|\Closure(MockObject&ShortClassResolverInterface):void $factory
     *
     * @return MockObject&ShortClassResolverInterface
     */
    public function createMockShortClassResolverInterface($factory = null)
    {
        $mockObject = $this->getMockBuilder('PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\ShortClassResolverInterface')->getMock();

        if ($factory instanceof \Closure) {
            $factory($mockObject);
        }

        return $mockObject;
    }

    /**
     * Create the mock SniffFullyQualifiedClassResolverInterface.
     *
     * @param null|\Closure(MockObject&SniffFullyQualifiedClassResolverInterface):void $factory
     *
     * @return MockObject&SniffFullyQualifiedClassResolverInterface
     */
    public function createMockSniffFullyQualifiedClassResolver($factory = null)
    {
        $mockObject = $this->createMock(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\FullyQualifiedClassResolver\\SniffFullyQualifiedClassResolverInterface'
        );

        if ($factory instanceof \Closure) {
            $factory($mockObject);
        }

        return $mockObject;
    }

    /**
     * Create the mock SniffGeneratorInterface.
     *
     * @param null|\Closure(MockObject&SniffGeneratorInterface):void $factory
     *
     * @return MockObject&SniffGeneratorInterface
     */
    public function createMockSniffGenerator($factory = null)
    {
        $mockObject = $this->getMockBuilder('PHPCSDevTools\\Scripts\\Scaffold\\Generator\\SniffGeneratorInterface')->getMock();

        if ($factory instanceof \Closure) {
            $factory($mockObject);
        }

        return $mockObject;
    }

    /**
     * Create the mock SniffNameInterface.
     *
     * @param null|\Closure(MockObject&SniffNameInterface):void $factory
     *
     * @return MockObject&SniffNameInterface
     */
    public function createMockSniffName($factory = null)
    {
        $mockObject = $this->getMockBuilder('PHPCSDevTools\\Scripts\\Scaffold\\SniffNameInterface')->getMock();

        if ($factory instanceof \Closure) {
            $factory($mockObject);
        }

        return $mockObject;
    }

    /**
     * Create the mock SniffNamespaceResolverInterface.
     *
     * @param null|\Closure(MockObject&SniffNamespaceResolverInterface):void $factory
     *
     * @return MockObject&SniffNamespaceResolverInterface
     */
    public function createMockSniffNamespaceResolver($factory = null)
    {
        $mockObject = $this->createMock(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\NamespaceResolver\\SniffNamespaceResolverInterface'
        );

        if ($factory instanceof \Closure) {
            $factory($mockObject);
        }

        return $mockObject;
    }

    /**
     * Create the mock SniffPathResolverInterface.
     *
     * @param null|\Closure(MockObject&SniffPathResolverInterface):void $factory
     *
     * @return MockObject&SniffPathResolverInterface
     */
    public function createMockSniffPathResolver($factory = null)
    {
        $mockObject = $this->createMock(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\PathResolver\\SniffPathResolverInterface'
        );

        if ($factory instanceof \Closure) {
            $factory($mockObject);
        }

        return $mockObject;
    }

    /**
     * Create the mock SniffShortClassResolverInterface.
     *
     * @param null|\Closure(MockObject&SniffShortClassResolverInterface):void $factory
     *
     * @return MockObject&SniffShortClassResolverInterface
     */
    public function createMockSniffShortClassResolver($factory = null)
    {
        $mockObject = $this->createMock(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\ShortClassResolver\\SniffShortClassResolverInterface'
        );

        if ($factory instanceof \Closure) {
            $factory($mockObject);
        }

        return $mockObject;
    }

    /**
     * Create the mock UnitTestFullyQualifiedClassResolverInterface.
     *
     * @param null|\Closure(MockObject&UnitTestFullyQualifiedClassResolverInterface):void $factory
     *
     * @return MockObject&UnitTestFullyQualifiedClassResolverInterface
     */
    public function createMockUnitTestFullyQualifiedClassResolver($factory = null)
    {
        $mockObject = $this->createMock(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\FullyQualifiedClassResolver\\UnitTestFullyQualifiedClassResolverInterface'
        );

        if ($factory instanceof \Closure) {
            $factory($mockObject);
        }

        return $mockObject;
    }

    /**
     * Create the mock UnitTestGeneratorInterface.
     *
     * @param null|\Closure(MockObject&UnitTestGeneratorInterface):void $factory
     *
     * @return MockObject&UnitTestGeneratorInterface
     */
    public function createMockUnitTestGenerator($factory = null)
    {
        $mockObject = $this->getMockBuilder('PHPCSDevTools\\Scripts\\Scaffold\\Generator\\UnitTestGeneratorInterface')->getMock();

        if ($factory instanceof \Closure) {
            $factory($mockObject);
        }

        return $mockObject;
    }

    /**
     * Create the mock UnitTestIncFixedGeneratorInterface.
     *
     * @param null|\Closure(MockObject&UnitTestIncFixedGeneratorInterface):void $factory
     *
     * @return MockObject&UnitTestIncFixedGeneratorInterface
     */
    public function createMockUnitTestIncFixedGenerator($factory = null)
    {
        $mockObject = $this->createMock(
            'PHPCSDevTools\\Scripts\\Scaffold\\Generator\\UnitTestIncFixedGeneratorInterface'
        );

        if ($factory instanceof \Closure) {
            $factory($mockObject);
        }

        return $mockObject;
    }

    /**
     * Create the mock UnitTestIncFixedPathResolverInterface.
     *
     * @param null|\Closure(MockObject&UnitTestIncFixedPathResolverInterface):void $factory
     *
     * @return MockObject&UnitTestIncFixedPathResolverInterface
     */
    public function createMockUnitTestIncFixedPathResolver($factory = null)
    {
        $mockObject = $this->createMock(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\PathResolver\\UnitTestIncFixedPathResolverInterface'
        );

        if ($factory instanceof \Closure) {
            $factory($mockObject);
        }

        return $mockObject;
    }

    /**
     * Create the mock UnitTestIncGeneratorInterface.
     *
     * @param null|\Closure(MockObject&UnitTestIncGeneratorInterface):void $factory
     *
     * @return MockObject&UnitTestIncGeneratorInterface
     */
    public function createMockUnitTestIncGenerator($factory = null)
    {
        $mockObject = $this->getMockBuilder('PHPCSDevTools\\Scripts\\Scaffold\\Generator\\UnitTestIncGeneratorInterface')->getMock();

        if ($factory instanceof \Closure) {
            $factory($mockObject);
        }

        return $mockObject;
    }

    /**
     * Create the mock UnitTestIncPathResolverInterface.
     *
     * @param null|\Closure(MockObject&UnitTestIncPathResolverInterface):void $factory
     *
     * @return MockObject&UnitTestIncPathResolverInterface
     */
    public function createMockUnitTestIncPathResolver($factory = null)
    {
        $mockObject = $this->createMock(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\PathResolver\\UnitTestIncPathResolverInterface'
        );

        if ($factory instanceof \Closure) {
            $factory($mockObject);
        }

        return $mockObject;
    }

    /**
     * Create the mock UnitTestNamespaceResolverInterface.
     *
     * @param null|\Closure(MockObject&UnitTestNamespaceResolverInterface):void $factory
     *
     * @return MockObject&UnitTestNamespaceResolverInterface
     */
    public function createMockUnitTestNamespaceResolver($factory = null)
    {
        $mockObject = $this->createMock(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\NamespaceResolver\\UnitTestNamespaceResolverInterface'
        );

        if ($factory instanceof \Closure) {
            $factory($mockObject);
        }

        return $mockObject;
    }

    /**
     * Create the mock UnitTestPathResolverInterface.
     *
     * @param null|\Closure(MockObject&UnitTestPathResolverInterface):void $factory
     *
     * @return MockObject&UnitTestPathResolverInterface
     */
    public function createMockUnitTestPathResolver($factory = null)
    {
        $mockObject = $this->createMock(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\PathResolver\\UnitTestPathResolverInterface'
        );

        if ($factory instanceof \Closure) {
            $factory($mockObject);
        }

        return $mockObject;
    }

    /**
     * Create the mock UnitTestShortClassResolverInterface.
     *
     * @param null|\Closure(MockObject&UnitTestShortClassResolverInterface):void $factory
     *
     * @return MockObject&UnitTestShortClassResolverInterface
     */
    public function createMockUnitTestShortClassResolver($factory = null)
    {
        $mockObject = $this->createMock(
            'PHPCSDevTools\\Scripts\\Scaffold\\Resolver\\ShortClassResolver\\UnitTestShortClassResolverInterface'
        );

        if ($factory instanceof \Closure) {
            $factory($mockObject);
        }

        return $mockObject;
    }

    /**
     * Create the mock WorkspaceInterface.
     *
     * @param null|\Closure(MockObject&WorkspaceInterface):void $factory
     *
     * @return MockObject&WorkspaceInterface
     */
    public function createMockWorkspace($factory = null)
    {
        $mockObject = $this->getMockBuilder('PHPCSDevTools\\Scripts\\Scaffold\\WorkspaceInterface')->getMock();

        if ($factory instanceof \Closure) {
            $factory($mockObject);
        }

        return $mockObject;
    }

    /**
     * Create the mock Writer.
     *
     * @param null|\Closure(MockObject&Writer):void $factory a factory to configure the mock with, if needed
     *
     * @return MockObject&Writer
     */
    public function createMockWriter($factory = null)
    {
        $mockObject = $this->getMockBuilder('PHPCSDevTools\\Scripts\\Utils\\Writer')->getMock();

        if ($factory instanceof \Closure) {
            $factory($mockObject);
        }

        return $mockObject;
    }

    /**
     * Create a renderer for the supplied filesystem double.
     *
     * @param FilesystemInterface $filesystem the filesystem mock to inject
     *
     * @throws ScaffolderException
     *
     * @return TemplateRendererInterface
     */
    public function createTemplateRenderer($filesystem)
    {
        return new TemplateRenderer($filesystem);
    }
}
