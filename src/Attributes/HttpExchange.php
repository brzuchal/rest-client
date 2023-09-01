<?php declare(strict_types=1);

namespace Brzuchal\RestClient\Attributes;

/**
 * @author Michał Brzuchalski <michal.brzuchalski@gmail.com>
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class HttpExchange
{
    public function __construct(
        public readonly string|null $baseUri = null,
        public readonly array $defaultHeaders = [],
        public readonly array $defaultContext = [],
        public readonly array $groups = [],
        public readonly string $accept = 'application/json',
    ) {
    }
}
