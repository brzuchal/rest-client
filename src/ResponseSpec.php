<?php

declare(strict_types=1);

namespace Brzuchal\RestClient;

use Closure;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * A mutable builder responsible for configuring interaction with {@link ResponseInterface}.
 */
final class ResponseSpec
{
    /** @var array<positive-int,Closure(ResponseInterface,SerializerInterface):object|null>|null */
    private array $errorHandlers = [];

    public function __construct(
        private readonly ResponseInterface $response,
        private readonly SerializerInterface $serializer,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function toArray(): array|null
    {
        if (ResponseHelper::is4xxStatusCode($this->response)) {
            return null;
        }

        return $this->response->toArray();
    }

    /**
     * @param class-string<T> $type
     *
     * @return T|null
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws UnknownContentType
     *
     * @template T
     */
    public function toEntity(string $type): object|null
    {
        return (new DefaultResponseExchange($type, $this->errorHandlers))
            ->exchange($this->response, $this->serializer);
    }

    /**
     * @param class-string<T> $type
     *
     * @return iterable<T>
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws UnknownContentType
     *
     * @template T
     */
    public function toEntityCollection(string $type): iterable
    {
        return (new DefaultResponseExchange($type . '[]', $this->errorHandlers))
            ->exchange($this->response, $this->serializer);
    }

    /** @throws TransportExceptionInterface */
    public function is2xxStatus(): bool
    {
        return ResponseHelper::is2xxStatusCode($this->response);
    }

    /**
     * Provide a function to map specific error status codes to an error handler.
     * <p>By default, if there are no matching status handlers, responses with
     * status codes &gt;= 400 wil throw a {@link RestClientResponseException}.
     *
     * @param int                                                            $statusCode   to match responses with
     * @param Closure(ResponseInterface,SerializerInterface):possible-object $errorHandler handler that typically, though not necessarily,
     * throws an exception
     *
     * @psalm-type possible-object = object|null
     */
    public function onStatus(int $statusCode, Closure $errorHandler): self
    {
        $this->errorHandlers[$statusCode] = $errorHandler;

        return $this;
    }
}
