<?php

declare(strict_types=1);

namespace Brzuchal\RestClient\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class EntityCollectionExchange extends EntityExchange
{
    /**
     * @param class-string          $entityType
     * @param non-empty-string      $uri
     * @param non-empty-string|null $acceptableMediaType
     * @param non-empty-string|null $acceptableCharset
     */
    public function __construct(
        public readonly string $entityType,
        string $uri = '/',
        string|null $acceptableMediaType = null,
        string|null $acceptableCharset = null,
    ) {
        parent::__construct(
            uri: $uri,
            acceptableMediaType: $acceptableMediaType,
            acceptableCharset: $acceptableCharset,
        );
    }
}
