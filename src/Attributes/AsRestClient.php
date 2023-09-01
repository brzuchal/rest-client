<?php declare(strict_types=1);

namespace Brzuchal\RestClient\Attributes;

/**
 * @author Michał Brzuchalski <michal.brzuchalski@gmail.com>
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class AsRestClient
{
    public function __construct(
        public readonly string|null $baseUri = null,
        public readonly string|null $parameter = null,
    ) {}
}
