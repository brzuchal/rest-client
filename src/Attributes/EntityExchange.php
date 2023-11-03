<?php

declare(strict_types=1);

namespace Brzuchal\RestClient\Attributes;

abstract class EntityExchange
{
    /**
     * @param non-empty-string      $uri
     * @param non-empty-string|null $acceptableMediaType
     * @param non-empty-string|null $acceptableCharset
     */
    public function __construct(
        public readonly string $uri = '/',
        public readonly string|null $acceptableMediaType = null,
        public readonly string|null $acceptableCharset = null,
    ) {
    }
}
