<?php declare(strict_types=1);

namespace Brzuchal\RestClient\Attributes;

/**
 * @author Michał Brzuchalski <michal.brzuchalski@gmail.com>
 */
#[\Attribute(\Attribute::TARGET_PARAMETER | \Attribute::IS_REPEATABLE)]
final class RequestParam
{
    public function __construct(
        public readonly string|null $name = null,
        public readonly string|null $property = null,
    ) {
    }
}
