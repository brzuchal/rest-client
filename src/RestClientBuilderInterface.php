<?php

declare(strict_types=1);

namespace Brzuchal\RestClient;

/**
 * A mutable builder for creating a {@link RestClientInterface} instances.
 */
interface RestClientBuilderInterface
{
    /**
     * Specify a base URI for all requests using the URI template.
     *
     * @param non-empty-string|null $url
     *
     * @return $this
     */
    public function baseUrl(string|null $url): static;

    /**
     * Specify a base URI template list of variables.
     *
     * @param array<non-empty-string,mixed> $uriVariables
     *
     * @return $this
     */
    public function defaultUriVariables(array $uriVariables): static;

    /**
     * Configure default headers common for all requests send.
     *
     * @param array<non-empty-string,list<non-empty-string>> $headers
     *
     * @return $this
     */
    public function defaultHeaders(array $headers): static;

    /**
     * Configure default header by name common for all requests send.
     *
     * @param non-empty-string $name
     * @param non-empty-string $value
     *
     * @return $this
     */
    public function defaultHeader(string $name, string $value): static;

    /**
     * Configure default serialization context to apply on de-/serialization.
     *
     * @param array<non-empty-string,mixed> $context
     *
     * @return $this
     */
    public function defaultContext(array $context): static;

    public function build(): RestClientInterface;
}
