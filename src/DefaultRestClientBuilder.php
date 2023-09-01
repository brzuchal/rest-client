<?php declare(strict_types=1);

namespace Brzuchal\RestClient;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Default implementation of {@link RestClientBuilderInterface}
 *
 * @author Michał Brzuchalski <michal.brzuchalski@gmail.com>
 */
final class DefaultRestClientBuilder implements RestClientBuilderInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private SerializerInterface $serializer,
        private array $defaultHeaders = [],
        private array $defaultContext = [],
    ) {
    }

    /** @inheritdoc */
    public function defaultHeaders(array $headers): self
    {
        foreach ($headers as $name => $values) {
            $name = strtolower($name);
            $this->defaultHeaders[$name] ??= [];
            $this->defaultHeaders[$name] += $values;
        }

        return $this;
    }

    /** @inheritdoc */
    public function defaultHeader(string $name, mixed $value): self
    {
        $name = strtolower($name);
        $this->defaultHeaders[$name] ??= [];
        $this->defaultHeaders[$name][] = $value;

        return $this;
    }

    /** @inheritdoc */
    public function defaultContext(array $context): self
    {
        foreach ($context as $name => $value) {
            $this->defaultContext[$name] = $value;
        }

        return $this;
    }

    public function build(): RestClientInterface
    {
        return new DefaultRestClient(
            $this->httpClient,
            $this->serializer,
            $this->defaultHeaders,
            $this->defaultContext,
        );
    }
}
