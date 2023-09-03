<?php declare(strict_types=1);

namespace Brzuchal\RestClient;

/**
 * A mutable builder for creating a {@link RestClientInterface} instances.
 *
 * @author Michał Marcin Brzuchalski <michal.brzuchalski@gmail.com>
 */
interface RestClientBuilderInterface
{
    /**
     * Specify a base URI for all requests using the URI template.
     *
     * @param string $url
     * @return $this
     */
    public function baseUrl(string $url): static;

    /**
     * Specify a base URI template list of variables.
     *
     * @param array $uriVariables
     * @return $this
     */
    public function defaultUriVariables(array $uriVariables): static;

    /**
     * Configure default headers common for all requests send.
     *
     * @param string[]|string[][] $headers
     * @return $this
     */
    public function defaultHeaders(array $headers): static;

    /**
     * Configure default header by name common for all requests send.
     *
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function defaultHeader(string $name, string $value): static;

    /**
     * Configure default serialization context to apply on de-/serialization.
     *
     * @param array $context
     * @return $this
     */
    public function defaultContext(array $context): static;

    public function build(): RestClientInterface;
}
