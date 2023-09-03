<?php

namespace Brzuchal\RestClient\Tests;

use Brzuchal\RestClient\RestClient;
use Brzuchal\RestClient\Tests\Fixtures\Todo;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class RestClientTest extends TestCase
{
    private const CONTENT_TYPE_JSON = 'application/json';
    private const SINGLE_TODO = <<<JSON
{
  "userId": 1,
  "id": 1,
  "title": "delectus aut autem",
  "completed": false
}
JSON;

    public function testWithBaseUri(): void
    {
        $client = new MockHttpClient(function (string $method, string $url, array $options) {
            $this->assertEquals('GET', $method);
            $this->assertEquals('https://example.com/todos', $url);

            return new MockResponse(self::SINGLE_TODO);
        });
        $restClient = RestClient::create('https://example.com', $client);
        $restClient->get('/todos')
            ->retrieve()
            ->toArray();
        $this->assertEquals(1, $client->getRequestsCount());
    }

    public function testToEntity(): void
    {
        $client = new MockHttpClient(function (string $method, string $url, array $options): ResponseInterface {
            $this->assertEquals('GET', $method);
            $this->assertEquals('https://example.com/todos', $url);

            return new MockResponse(self::SINGLE_TODO, [
                'response_headers' => ['content-type' => self::CONTENT_TYPE_JSON],
            ]);
        });
        $restClient = RestClient::create('https://example.com/', $client);
        $todo = $restClient->get('/todos')
            ->retrieve()
            ->toEntity(Todo::class);
        $this->assertInstanceOf(Todo::class, $todo);
    }
}
