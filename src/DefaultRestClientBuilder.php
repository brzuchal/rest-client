<?php

declare(strict_types=1);

namespace Brzuchal\RestClient;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function is_string;
use function strtolower;

/**
 * Default implementation of {@link RestClientBuilderInterface}
 */
final class DefaultRestClientBuilder implements RestClientBuilderInterface
{
    /**
     * @param non-empty-string|null                          $baseUrl
     * @param array<non-empty-string,mixed>|null             $defaultUriVariables
     * @param array<non-empty-string,list<non-empty-string>> $defaultHeaders
     * @param array<non-empty-string,mixed>                  $defaultContext
     */
    public function __construct(
        private readonly HttpClientInterface|null $httpClient = null,
        private readonly SerializerInterface|null $serializer = null,
        private string|null $baseUrl = null,
        private array|null $defaultUriVariables = null,
        private array $defaultHeaders = [],
        private array $defaultContext = [],
    ) {
    }

    public function baseUrl(string|null $url): static
    {
        $this->baseUrl = $url;

        return $this;
    }

    /** @inheritdoc */
    public function defaultUriVariables(array $uriVariables): static
    {
        $this->defaultUriVariables ??= [];
        foreach ($uriVariables as $name => $value) {
            $this->defaultUriVariables[$name] = $value;
        }

        return $this;
    }

    /** @inheritdoc */
    public function defaultHeaders(array $headers): static
    {
        foreach ($headers as $name => $values) {
            $name                          = strtolower($name);
            $this->defaultHeaders[$name] ??= [];
            if (is_string($values)) {
                $this->defaultHeaders[$name][] = $values;
            } else {
                $this->defaultHeaders[$name] += $values;
            }
        }

        return $this;
    }

    public function defaultHeader(string $name, mixed $value): static
    {
        $name                          = strtolower($name);
        $this->defaultHeaders[$name] ??= [];
        $this->defaultHeaders[$name][] = $value;

        return $this;
    }

    /** @inheritdoc */
    public function defaultContext(array $context): static
    {
        foreach ($context as $name => $value) {
            $this->defaultContext[$name] = $value;
        }

        return $this;
    }

    public function build(): RestClientInterface
    {
        return new DefaultRestClient(
            $this->httpClient ?? HttpClient::create(),
            $this->serializer ?? self::createDefaultSerializer(),
            $this->baseUrl,
            $this->defaultUriVariables,
            $this->defaultHeaders,
            $this->defaultContext,
        );
    }

    protected static function createDefaultSerializer(): Serializer
    {
        return new Serializer([
            new ArrayDenormalizer(),
            new DateTimeNormalizer(),
            new ObjectNormalizer(),
        ], [
            new JsonEncoder(),
        ]);
    }
}
