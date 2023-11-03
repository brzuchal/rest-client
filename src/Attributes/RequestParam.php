<?php

declare(strict_types=1);

namespace Brzuchal\RestClient\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::IS_REPEATABLE)]
final class RequestParam
{
    /**
     * @param non-empty-string|null $name
     * @param non-empty-string|null $property
     */
    public function __construct(
        public readonly string|null $name = null,
        public readonly string|null $property = null,
    ) {
    }
}
