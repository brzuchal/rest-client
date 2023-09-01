<?php declare(strict_types=1);

namespace Brzuchal\RestClient\Attributes;

/**
 * @author Michał Brzuchalski <michal.brzuchalski@gmail.com>
 */
#[\Attribute(\Attribute::TARGET_PARAMETER)]
final class RequestBody
{
    public function __construct(
        public readonly array $groups = [],
        public readonly string $contentType = 'application/json',
    ) {
    }
}
