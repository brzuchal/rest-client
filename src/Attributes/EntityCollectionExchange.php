<?php

declare(strict_types=1);

namespace Brzuchal\RestClient\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class EntityCollectionExchange extends EntityExchange
{
    public function __construct(
        public readonly string $entityType,
        public readonly string $uri = '/',
        public readonly string|null $acceptableMediaType = null,
        public readonly string|null $acceptableCharset = null,
    ) {
    }
}
