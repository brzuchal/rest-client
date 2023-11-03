<?php

declare(strict_types=1);

namespace Brzuchal\RestClient;

use Symfony\Component\Mime\MimeTypes;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

use function array_key_exists;
use function explode;
use function str_starts_with;

/**
 * Helper class containing a utility functions to interact with {@link ResponseInterface}
 */
final class ResponseHelper
{
    /**
     * Extracts a format for deserialization from request based on {@code Content-Type} header.
     *
     * @throws TransportExceptionInterface
     * @throws UnknownContentType
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public static function extractFormatFromResponse(ResponseInterface $response): string
    {
        $headers = $response->getHeaders();
        if (! array_key_exists('content-type', $headers)) {
            throw new UnknownContentType();
        }

        $contentType = $headers['content-type'][0];

        return self::extractFormat($contentType);
    }

    /**
     * Extracts a format for deserialization from {@code Content-Type} header.
     */
    public static function extractFormat(string $contentType): string|null
    {
        if (str_starts_with($contentType, 'application/json')) {
            return 'json';
        }

        $mimeTypes = new MimeTypes();
        $mimeType  = explode(';', $contentType, 2)[0];

        return $mimeTypes->getExtensions($mimeType)[0] ?? null;
    }

    /**
     * Checks if the response status code is between 200 and 299.
     *
     * @throws TransportExceptionInterface
     */
    public static function is2xxStatusCode(ResponseInterface $response): bool
    {
        return $response->getStatusCode() >= 200 && $response->getStatusCode() < 300;
    }

    /**
     * Checks if the response status code is between 400 and 499.
     *
     * @throws TransportExceptionInterface
     */
    public static function is4xxStatusCode(ResponseInterface $response): bool
    {
        return $response->getStatusCode() >= 400 && $response->getStatusCode() < 500;
    }
}
