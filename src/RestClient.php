<?php declare(strict_types=1);

namespace Brzuchal\RestClient;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * A factory to instantiate the {@link DefaultRestClient}
 * and {@link DefaultRestClientBuilder} for the runtime.
 *
 * @author Michał Brzuchalski <michal.brzuchalski@gmail.com>
 */
final class RestClient
{
    public static function create(
        string $baseUri,
        HttpClientInterface|null $httpClient = null,
        SerializerInterface|null $serializer = null,
    ): RestClientInterface {
        return new DefaultRestClient(
            $httpClient ?? HttpClient::createForBaseUri($baseUri),
            $serializer ?? self::createDefaultSerializer(),
        );
    }

    public static function builder(
        HttpClientInterface|null $httpClient = null,
        SerializerInterface|null $serializer = null,
    ): RestClientBuilderInterface {
        return new DefaultRestClientBuilder(
            $httpClient ?? HttpClient::create(),
            $serializer ?? self::createDefaultSerializer(),
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
