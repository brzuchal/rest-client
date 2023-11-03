<?php

declare(strict_types=1);

namespace Brzuchal\RestClient;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

use function array_key_exists;

/**
 * Default {@link ResponseExchange} effectively executing HTTP request.
 * Used by {@link ResponseSpec::toEntity()} and {@link ResponseSpec::toEntityCollection()}.
 */
final class DefaultResponseExchange implements ResponseExchange
{
    /** @var array<positive-int,Closure(ResponseInterface,SerializerInterface):object|null> */
    private readonly array $errorHandlers;

    /** @param array<positive-int,Closure(ResponseInterface,SerializerInterface):object|null>|null $errorHandlers */
    public function __construct(
        private readonly string $type,
        array|null $errorHandlers = [],
    ) {
        $this->errorHandlers = $errorHandlers ?? [];
    }

    /**
     * @param array $context
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws UnknownContentType
     */
    public function exchange(
        ResponseInterface $response,
        SerializerInterface $serializer,
        array $context = [],
    ): object|null {
        if (array_key_exists($response->getStatusCode(), $this->errorHandlers)) {
            return $this->errorHandlers[$response->getStatusCode()]($response, $serializer);
        }

        return $serializer->deserialize(
            data: $response->getContent(),
            type: $this->type,
            format: ResponseHelper::extractFormatFromResponse($response),
            context: $context,
        );
    }
}
