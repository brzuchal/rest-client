<?php

declare(strict_types=1);

namespace Brzuchal\RestClient\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
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
