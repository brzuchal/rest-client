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
     * Configure default headers common for all requests send.
     *
     * @param string[]|string[][] $headers
     * @return $this
     */
    public function defaultHeaders(array $headers): self;

    /**
     * Configure default header by name common for all requests send.
     *
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function defaultHeader(string $name, string $value): self;

    /**
     * Configure default serialization context to apply on de-/serialization.
     *
     * @param array $context
     * @return $this
     */
    public function defaultContext(array $context): self;

    public function build(): RestClientInterface;
}
