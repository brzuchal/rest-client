<?php

declare(strict_types=1);

namespace Brzuchal\RestClient;

/**
 * Client to perform HTTP requests, exposing a fluent, synchronous API over
 * underlying HTTP client.
 */
interface RestClientInterface
{
    /**
     * Start building an HTTP GET request.
     */
    public function get(string|null $uri = null, array|null $uriVariables = null): RequestHeadersSpec;

    /**
     * Start building an HTTP HEAD request.
     */
    public function head(string|null $uri = null, array|null $uriVariables = null): RequestHeadersSpec;

    /**
     * Start building an HTTP POST request.
     */
    public function post(string|null $uri = null, array|null $uriVariables = null): RequestBodyHeadersSpec;

    /**
     * Start building an HTTP PUT request.
     */
    public function put(string|null $uri = null, array|null $uriVariables = null): RequestBodyHeadersSpec;

    /**
     * Start building an HTTP PATCH request.
     */
    public function patch(string|null $uri = null, array|null $uriVariables = null): RequestBodyHeadersSpec;

    /**
     * Start building an HTTP DELETE request.
     */
    public function delete(string|null $uri = null, array|null $uriVariables = null): RequestHeadersSpec;

    /**
     * Start building an HTTP OPTIONS request.
     */
    public function options(string|null $uri = null, array|null $uriVariables = null): RequestHeadersSpec;
}
