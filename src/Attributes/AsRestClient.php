<?php

declare(strict_types=1);

namespace Brzuchal\RestClient\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class AsRestClient
{
    /**
     * @param non-empty-string|null $baseUri
     * @param non-empty-string|null $parameter
     */
    public function __construct(
        public readonly string|null $baseUri = null,
        public readonly string|null $parameter = null,
    ) {
    }
}
