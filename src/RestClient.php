<?php

declare(strict_types=1);

namespace Brzuchal\RestClient;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * A factory to instantiate the {@link DefaultRestClient}
 * and {@link DefaultRestClientBuilder} for the runtime.
 */
final class RestClient
{
    public static function create(
        string $baseUri,
        HttpClientInterface|null $httpClient = null,
        SerializerInterface|null $serializer = null,
    ): RestClientInterface {
        return self::builder($httpClient, $serializer)
            ->baseUrl($baseUri)
            ->build();
    }

    public static function builder(
        HttpClientInterface|null $httpClient = null,
        SerializerInterface|null $serializer = null,
    ): RestClientBuilderInterface {
        return new DefaultRestClientBuilder($httpClient, $serializer);
    }
}
