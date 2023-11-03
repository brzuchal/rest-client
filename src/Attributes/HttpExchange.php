<?php

declare(strict_types=1);

namespace Brzuchal\RestClient\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class HttpExchange
{
    /**
     * @param non-empty-string|null                          $baseUri
     * @param array<non-empty-string,list<non-empty-string>> $defaultHeaders
     * @param array<non-empty-string,mixed>                  $defaultContext
     * @param list<non-empty-string>                         $groups
     * @param non-empty-string                               $accept
     */
    public function __construct(
        public readonly string|null $baseUri = null,
        public readonly array $defaultHeaders = [],
        public readonly array $defaultContext = [],
        public readonly array $groups = [],
        public readonly string $accept = 'application/json',
    ) {
    }
}
