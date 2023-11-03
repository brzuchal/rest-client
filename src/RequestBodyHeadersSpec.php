<?php

declare(strict_types=1);

namespace Brzuchal\RestClient;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * A mutable builder responsible for configuring request containing body.
 */
final class RequestBodyHeadersSpec extends RequestHeadersSpec
{
    private object $body;

    /**
     * Set the {@code Content-Type} of the body to be specified in the headers.
     *
     * @param string $contentType the content type
     *
     * @return $this
     */
    public function contentType(string $contentType): self
    {
        $this->headers['content-type'] = $contentType;

        return $this;
    }

    /**
     * Set the body of the request to the given {@code object}.
     *
     * @param object $body the body of the response
     *
     * @return $this
     */
    public function body(object $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Create a response object by underlying HTTP client.
     *
     * @throws TransportExceptionInterface
     */
    protected function request(): ResponseInterface
    {
        if (empty($this->headers['content-type'])) {
            $format                        = 'json';
            $this->headers['content-type'] = 'application/json';
        } else {
            $format = ResponseHelper::extractFormat($this->headers['content-type']);
        }

        return $this->httpClient->request(
            $this->method,
            $this->uriFactory->render(),
            [
                'headers' => $this->headers,
                'body' => $this->serializer->serialize(
                    data: $this->body,
                    format: $format,
                    context: $this->defaultContext,
                ),
            ],
        );
    }
}
