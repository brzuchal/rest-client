<?php

declare(strict_types=1);

namespace Brzuchal\RestClient\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
final class RequestBody
{
    /**
     * @param list<non-empty-string> $groups
     * @param non-empty-string       $contentType
     */
    public function __construct(
        public readonly array $groups = [],
        public readonly string $contentType = 'application/json',
    ) {
    }
}
