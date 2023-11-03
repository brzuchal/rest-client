<?php

declare(strict_types=1);

namespace Brzuchal\RestClient\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
final class RequestBody
{
    public function __construct(
        public readonly array $groups = [],
        public readonly string $contentType = 'application/json',
    ) {
    }
}
