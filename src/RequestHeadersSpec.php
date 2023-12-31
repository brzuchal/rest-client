<?php

declare(strict_types=1);

namespace Brzuchal\RestClient;

use Closure;
use DateTimeInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

use function implode;

/**
 * A mutable builder responsible for configuring request without body.
 */
class RequestHeadersSpec
{
    /** @var array<non-empty-string,list<non-empty-string>> */
    protected array $headers;
    protected UriFactory $uriFactory;

    /**
     * @param non-empty-string                               $method
     * @param array<non-empty-string,list<non-empty-string>> $defaultHeaders
     * @param array<non-empty-string,mixed>                  $defaultContext
     */
    public function __construct(
        protected readonly string $method,
        protected HttpClientInterface $httpClient,
        protected SerializerInterface $serializer,
        UriFactory|null $uriFactory = null,
        array $defaultHeaders = [],
        protected array $defaultContext = [],
    ) {
        $this->uriFactory = $uriFactory ?? UriFactory::create();
        $this->headers    = $defaultHeaders;
    }

    /**
     * Specify a URI for the request using the URI template and list of variables if required.
     * This method is mutually exclusive to {@link self::uriFactory()}.
     *
     * @param non-empty-string|null         $uri
     * @param array<non-empty-string,mixed> $uriVariables
     *
     * @return $this
     */
    public function uri(string|null $uri = null, array|null $uriVariables = null): static
    {
        $this->uriFactory = UriFactory::create($uri, $uriVariables);

        return $this;
    }

    /**
     * Specify a URI factory for the request using a closure.
     * This method is mutually exclusive to {@link self::uri()}.
     *
     * @param Closure():string $closure
     *
     * @return $this
     */
    public function uriFactory(Closure $closure): static
    {
        $this->uriFactory = new UriFactory($closure);

        return $this;
    }

    /**
     * Set the list of acceptable media types, as
     * specified by the {@code Accept} header.
     *
     * @param list<non-empty-string> $acceptableMediaTypes
     *
     * @return $this
     */
    public function accept(string ...$acceptableMediaTypes): static
    {
        $this->headers['accept'] = $acceptableMediaTypes;

        return $this;
    }

    /**
     * Set the list of acceptable charsets, as specified
     * by the {@code Accept-Charset} header.
     *
     * @param list<non-empty-string> $acceptableCharsets
     *
     * @return $this
     */
    public function acceptCharset(string ...$acceptableCharsets): static
    {
        $this->headers['accept-charset'] = $acceptableCharsets;

        return $this;
    }

    /**
     * Set the value of the {@code If-Modified-Since} header.
     * <p>The date should be specified as the number of milliseconds since
     * January 1, 1970 GMT.
     *
     * @return $this
     */
    public function ifModifiedSince(DateTimeInterface $ifModifiedSince): static
    {
        $this->headers['if-modified-since'] = $ifModifiedSince->format(DateTimeInterface::RFC7231);

        return $this;
    }

    /**
     * Set the values of the {@code If-None-Match} header.
     *
     * @param list<non-empty-string> $ifNoneMatches
     *
     * @return $this
     */
    public function ifNoneMatch(string ...$ifNoneMatches): static
    {
        $this->headers['if-modified-since'] = '"' . implode('", "', $ifNoneMatches) . '"';

        return $this;
    }

    /**
     * Add the given, single header value under the given name.
     *
     * @param non-empty-string       $name
     * @param list<non-empty-string> $values
     *
     * @return $this
     */
    public function header(string $name, string ...$values): static
    {
        $this->headers[$name] ??= [];
        $this->headers[$name]  += $values;

        return $this;
    }

    /**
     * Proceed to declare interaction with underlying HTTP client response.
     *
     * @throws TransportExceptionInterface
     */
    public function retrieve(): ResponseSpec
    {
        return new ResponseSpec($this->request(), $this->serializer);
    }

    /**
     * Exchange the {@link ResponseInterface} for an {@code object}
     * using a {@code Closure} or an instance of {@link ResponseExchange}.
     *
     * @throws TransportExceptionInterface
     */
    public function exchange(ResponseExchange|Closure $closure): object|null
    {
        if ($closure instanceof ResponseExchange) {
            $closure = $closure->exchange(...);
        }

        return $closure($this->request(), $this->serializer, $this->defaultContext);
    }

    /** @throws TransportExceptionInterface */
    protected function request(): ResponseInterface
    {
        return $this->httpClient->request(
            $this->method,
            $this->uriFactory->render(),
            ['headers' => $this->headers],
        );
    }
}
