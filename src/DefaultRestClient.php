<?php declare(strict_types=1);

namespace Brzuchal\RestClient;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Default implementation of {@link RestClientInterface}.
 *
 * @author Michał Brzuchalski <michal.brzuchalski@gmail.com>
 */
final class DefaultRestClient implements RestClientInterface
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly SerializerInterface $serializer,
        private readonly string|null $baseUrl = null,
        private readonly array|null $defaultUriVariables = null,
        private readonly array $defaultHeaders = [],
        private readonly array $defaultContext = [],
    ) {
    }

    /** @inheritdoc */
    public function get(string|null $uri = null, array|null $uriVariables = null): RequestHeadersSpec
    {
        return $this->createHeadersSpec('GET', $this->createUriFactory($uri, $uriVariables));
    }

    /** @inheritdoc */
    public function head(string|null $uri = null, array|null $uriVariables = null): RequestHeadersSpec
    {
        return $this->createHeadersSpec('HEAD', $this->createUriFactory($uri, $uriVariables));
    }

    /** @inheritdoc */
    public function post(string|null $uri = null, array|null $uriVariables = null): RequestBodyHeadersSpec
    {
        return $this->createBodyHeadersSpec('POST', $this->createUriFactory($uri, $uriVariables));
    }

    /** @inheritdoc */
    public function put(string|null $uri = null, array|null $uriVariables = null): RequestBodyHeadersSpec
    {
        return $this->createBodyHeadersSpec('PUT', $this->createUriFactory($uri, $uriVariables));
    }

    /** @inheritdoc */
    public function patch(string|null $uri = null, array|null $uriVariables = null): RequestBodyHeadersSpec
    {
        return $this->createBodyHeadersSpec('PATCH', $this->createUriFactory($uri, $uriVariables));
    }

    /** @inheritdoc */
    public function delete(string|null $uri = null, array|null $uriVariables = null): RequestHeadersSpec
    {
        return $this->createHeadersSpec('DELETE', $this->createUriFactory($uri, $uriVariables));
    }

    /** @inheritdoc */
    public function options(string|null $uri = null, array|null $uriVariables = null): RequestHeadersSpec
    {
        return $this->createHeadersSpec('OPTIONS', $this->createUriFactory($uri, $uriVariables));
    }

    protected function createHeadersSpec(string $method, UriFactory $uriFactory): RequestHeadersSpec
    {
        return new RequestHeadersSpec(
            method: $method,
            httpClient: $this->httpClient,
            serializer: $this->serializer,
            uriFactory: $uriFactory,
            defaultHeaders: $this->defaultHeaders,
            defaultContext: $this->defaultContext,
        );
    }

    protected function createBodyHeadersSpec(string $method, UriFactory $uriFactory): RequestBodyHeadersSpec
    {
        return new RequestBodyHeadersSpec(
            method: $method,
            httpClient: $this->httpClient,
            serializer: $this->serializer,
            uriFactory: $uriFactory,
            defaultHeaders: $this->defaultHeaders,
            defaultContext: $this->defaultContext,
        );
    }

    protected function createUriFactory(string|null $uri, array|null $uriVariables): UriFactory
    {
        if ($this->baseUrl) {
            if (str_ends_with($this->baseUrl, '/') && str_starts_with($uri, '/')) {
                $uri = ltrim($uri, '/');
            }

            $uri = $this->baseUrl . $uri;
        }

        if ($this->defaultUriVariables && $uriVariables) {
            $uriVariables = array_merge($this->defaultUriVariables, $uriVariables);
        }

        return UriFactory::create($uri, $uriVariables ?? $this->defaultUriVariables);
    }
}
