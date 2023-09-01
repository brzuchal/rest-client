<?php declare(strict_types=1);

namespace Brzuchal\RestClient\Attributes;

/**
 * @author Michał Brzuchalski <michal.brzuchalski@gmail.com>
 */
#[\Attribute(\Attribute::TARGET_PARAMETER)]
final class UriParam
{
    public function __construct(
        public readonly string|null $name = null,
        public readonly string|null $property = null,
    ) {
    }
}
