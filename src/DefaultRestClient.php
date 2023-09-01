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
        private readonly array $defaultHeaders = [],
        private readonly array $defaultContext = [],
    ) {
    }

    /** @inheritdoc */
    public function get(string|null $uri = null, array|null $uriVariables = null): RequestHeadersSpec
    {
        return $this->createHeadersSpec('GET', UriFactory::create($uri, $uriVariables));
    }

    /** @inheritdoc */
    public function head(string|null $uri = null, array|null $uriVariables = null): RequestHeadersSpec
    {
        return $this->createHeadersSpec('HEAD', UriFactory::create($uri, $uriVariables));
    }

    /** @inheritdoc */
    public function post(string|null $uri = null, array|null $uriVariables = null): RequestBodyHeadersSpec
    {
        return $this->createBodyHeadersSpec('POST', UriFactory::create($uri, $uriVariables));
    }

    /** @inheritdoc */
    public function put(string|null $uri = null, array|null $uriVariables = null): RequestBodyHeadersSpec
    {
        return $this->createBodyHeadersSpec('PUT', UriFactory::create($uri, $uriVariables));
    }

    /** @inheritdoc */
    public function patch(string|null $uri = null, array|null $uriVariables = null): RequestBodyHeadersSpec
    {
        return $this->createBodyHeadersSpec('PATCH', UriFactory::create($uri, $uriVariables));
    }

    /** @inheritdoc */
    public function delete(string|null $uri = null, array|null $uriVariables = null): RequestHeadersSpec
    {
        return $this->createHeadersSpec('DELETE', UriFactory::create($uri, $uriVariables));
    }

    /** @inheritdoc */
    public function options(string|null $uri = null, array|null $uriVariables = null): RequestHeadersSpec
    {
        return $this->createHeadersSpec('OPTIONS', UriFactory::create($uri, $uriVariables));
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
}
