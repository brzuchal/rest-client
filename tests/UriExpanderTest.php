<?php

namespace Brzuchal\RestClient\Tests;

use Brzuchal\RestClient\UriExpander;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class UriExpanderTest extends TestCase
{
    #[DataProvider('dataExpand')]
    public function testSupports(string $uri): void
    {
        $this->assertTrue(UriExpander::supports($uri));
    }

    #[DataProvider('dataExpand')]
    public function testExpand(string $uri, array $vars, string $expected): void
    {
        $expander = new UriExpander($uri);
        $this->assertEquals($expected, $expander->expand($vars));
    }

    public static function dataExpand(): iterable
    {
        yield 'path' => [
            'https://example.com/todos/{id}',
            ['id' => 123],
            'https://example.com/todos/123',
        ];

        yield 'host' => [
            'https://{host}/todos/',
            ['host' => 'example.com'],
            'https://example.com/todos/',
        ];

        yield 'multiple' => [
            'https://{host}/todos/{todoId}/acceptions/{acception_id}',
            ['host' => 'example.com', 'todoId' => 123, 'acception_id' => 567],
            'https://example.com/todos/123/acceptions/567',
        ];
    }

    #[DataProvider('dataNotSupported')]
    public function testSupportsNot(string $uri): void
    {
        $this->assertFalse(UriExpander::supports($uri));
    }

    public static function dataNotSupported(): iterable
    {
        yield '{#var}' => ['https://example.com/todos/{#id}'];
        yield '{+var}' => ['https://example.com/todos/{+id}'];
        yield '{foo,bar}' => ['https://example.com/todos/{foo,bar}'];
    }
}
