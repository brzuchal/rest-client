<?php declare(strict_types=1);

namespace Brzuchal\RestClient;

use Closure;

/**
 * @author Michał Brzuchalski <michal.brzuchalski@gmail.com>
 */
final class UriFactory
{
    public static function create(
        string|null $uri = null,
        array|object|null $uriVariables = null,
    ): self {
        if ($uri === null || !str_contains($uri, '{')) {
            return new self(fn () => $uri ?? '/');
        }

        if (UriExpander::supports($uri)) {
            return new self(fn () => (new UriExpander($uri))->expand($uriVariables));
        }

        $expander = self::createExpanderFromPopularVendors();

        return new self(fn () => $expander($uri, $uriVariables));
    }


    /**
     * @return Closure(string $url, array $vars): string
     */
    private static function createExpanderFromPopularVendors(): Closure
    {
        if (class_exists(\GuzzleHttp\UriTemplate\UriTemplate::class)) {
            return \GuzzleHttp\UriTemplate\UriTemplate::expand(...);
        }

        if (class_exists(\League\Uri\UriTemplate::class)) {
            return static fn (string $url, array $vars): string => (string) (new \League\Uri\UriTemplate($url))->expand($vars);
        }

        if (class_exists(\Rize\UriTemplate::class)) {
            return (new \Rize\UriTemplate())->expand(...);
        }

        throw new \LogicException('Support for URI template requires a vendor to expand the URI. Run "composer require guzzlehttp/uri-template" or pass your own expander \Closure implementation.');
    }

    public function __construct(
        private Closure $uriFunction,
    ) {
    }

    public function render(): string
    {
        return $this->uriFunction->call($this);
    }
}
