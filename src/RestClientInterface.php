<?php declare(strict_types=1);

namespace Brzuchal\RestClient;

/**
 * Client to perform HTTP requests, exposing a fluent, synchronous API over
 * underlying HTTP client.
 *
 * @author Michał Brzuchalski <michal.brzuchalski@gmail.com>
 */
interface RestClientInterface
{
    /**
     * Start building an HTTP GET request.
     *
     * @param string|null $uri
     * @param array|null $uriVariables
     * @return RequestHeadersSpec
     */
    public function get(string|null $uri = null, array|null $uriVariables = null): RequestHeadersSpec;

    /**
     * Start building an HTTP HEAD request.
     *
     * @param string|null $uri
     * @param array|null $uriVariables
     * @return RequestHeadersSpec
     */
    public function head(string|null $uri = null, array|null $uriVariables = null): RequestHeadersSpec;

    /**
     * Start building an HTTP POST request.
     *
     * @param string|null $uri
     * @param array|null $uriVariables
     * @return RequestBodyHeadersSpec
     */
    public function post(string|null $uri = null, array|null $uriVariables = null): RequestBodyHeadersSpec;

    /**
     * Start building an HTTP PUT request.
     *
     * @param string|null $uri
     * @param array|null $uriVariables
     * @return RequestBodyHeadersSpec
     */
    public function put(string|null $uri = null, array|null $uriVariables = null): RequestBodyHeadersSpec;

    /**
     * Start building an HTTP PATCH request.
     *
     * @param string|null $uri
     * @param array|null $uriVariables
     * @return RequestBodyHeadersSpec
     */
    public function patch(string|null $uri = null, array|null $uriVariables = null): RequestBodyHeadersSpec;

    /**
     * Start building an HTTP DELETE request.
     *
     * @param string|null $uri
     * @param array|null $uriVariables
     * @return RequestHeadersSpec
     */
    public function delete(string|null $uri = null, array|null $uriVariables = null): RequestHeadersSpec;

    /**
     * Start building an HTTP OPTIONS request.
     *
     * @param string|null $uri
     * @param array|null $uriVariables
     * @return RequestHeadersSpec
     */
    public function options(string|null $uri = null, array|null $uriVariables = null): RequestHeadersSpec;
}
