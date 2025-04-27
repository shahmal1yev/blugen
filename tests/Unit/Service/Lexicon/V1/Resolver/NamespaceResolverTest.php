<?php

namespace Blugen\Tests\Unit\Service\Lexicon\V1\Resolver;

use Blugen\Enum\PrimaryTypeEnum;
use Blugen\Service\Lexicon\V1\Resolver\NamespaceResolver;
use Blugen\Service\Lexicon\LexiconInterface;
use Blugen\Service\Lexicon\DefinitionInterface;
use PHPUnit\Framework\TestCase;

class NamespaceResolverTest extends TestCase
{
    private NamespaceResolver $resolver;
    private LexiconInterface $lexiconMock;
    private DefinitionInterface $definitionMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resolver = new NamespaceResolver();
        $this->lexiconMock = $this->createMock(LexiconInterface::class);
        $this->definitionMock = $this->createMock(DefinitionInterface::class);
    }

    public function test_namespace_generates_correct_namespace_and_classname_for_primary_type(): void
    {
        $this->lexiconMock
            ->method('nsid')
            ->willReturn('app.user.profile');

        $this->lexiconMock
            ->method('defs')
            ->willReturn([
                'UserProfile' => ['type' => 'object']
            ]);

        $this->definitionMock
            ->method('name')
            ->willReturn('UserProfile');

        $this->definitionMock
            ->method('type')
            ->willReturn('object');

        [$namespace, $className] = $this->resolver->namespace($this->lexiconMock, $this->definitionMock);

        $this->assertSame('App\\User\\Profile', $namespace);
        $this->assertSame('UserProfile', $className);
    }

    public function test_namespace_fallbacks_to_last_nsid_part_for_non_primary_type(): void
    {
        $this->lexiconMock
            ->method('nsid')
            ->willReturn('app.custom.data');

        $this->lexiconMock
            ->method('defs')
            ->willReturn([
                'CustomData' => ['type' => 'x-custom'] // primary type deyil
            ]);

        $this->definitionMock
            ->method('name')
            ->willReturn('CustomData');

        $this->definitionMock
            ->method('type')
            ->willReturn(PrimaryTypeEnum::PROCEDURE->value);

        [$namespace, $className] = $this->resolver->namespace($this->lexiconMock, $this->definitionMock);

        $this->assertSame('App\\Custom', $namespace);
        $this->assertSame('Data', $className);
    }

    public function test_path_generates_correct_filesystem_path(): void
    {
        $this->lexiconMock
            ->method('nsid')
            ->willReturn('app.system.config');

        $this->lexiconMock
            ->method('defs')
            ->willReturn([
                'SystemConfig' => ['type' => 'object']
            ]);

        $this->definitionMock
            ->method('name')
            ->willReturn('SystemConfig');

        $this->definitionMock
            ->method('type')
            ->willReturn('object');

        $path = $this->resolver->path($this->lexiconMock, $this->definitionMock);

        $this->assertSame('App/System/Config/SystemConfig', $path);
    }

    public function test_it_throws_exception_if_definition_does_not_exist(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Definition "MissingDef" does not exist in the lexicon.');

        $this->lexiconMock
            ->method('nsid')
            ->willReturn('app.error.test');

        $this->lexiconMock
            ->method('defs')
            ->willReturn([
                // No definitions intentionally
            ]);

        $this->definitionMock
            ->method('name')
            ->willReturn('MissingDef');

        $this->resolver->namespace($this->lexiconMock, $this->definitionMock);
    }
}
