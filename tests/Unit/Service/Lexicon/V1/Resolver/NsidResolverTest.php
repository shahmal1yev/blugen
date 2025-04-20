<?php

namespace Blugen\Tests\Unit\Service\Lexicon\V1\Resolver;

use PHPUnit\Framework\TestCase;
use Blugen\Service\Lexicon\V1\Resolver\NsidResolver;

class NsidResolverTest extends TestCase
{
    public function testNamespaceWithoutFragment()
    {
        $nsid = 'com.example.post';
        $expected = 'Com\\Example\\Post';
        $this->assertSame($expected, NsidResolver::namespace($nsid));
    }

    public function testNamespaceWithFragment()
    {
        $nsid = 'com.example.post#mainFeed';
        $expected = 'Com\\Example\\Post\\MainFeed';
        $this->assertSame($expected, NsidResolver::namespace($nsid));
    }

    public function testNamespaceSingleComponent()
    {
        $nsid = 'foo';
        $expected = 'Foo';
        $this->assertSame($expected, NsidResolver::namespace($nsid));
    }

    public function testNamespaceSingleComponentWithFragment()
    {
        $nsid = 'foo#bar';
        $expected = 'Foo\\Bar';
        $this->assertSame($expected, NsidResolver::namespace($nsid));
    }
}
