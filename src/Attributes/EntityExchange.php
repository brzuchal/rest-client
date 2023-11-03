<?php

declare(strict_types=1);

namespace Brzuchal\RestClient\Attributes;

abstract class EntityExchange
{
    public function __construct(
        public readonly string $uri = '/',
        public readonly string|null $acceptableMediaType = null,
        public readonly string|null $acceptableCharset = null,
    ) {
    }
}
