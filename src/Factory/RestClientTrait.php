<?php

declare(strict_types=1);

namespace Brzuchal\RestClient\Factory;

use Brzuchal\RestClient\RestClientInterface;

// phpcs:disable
trait RestClientTrait
// phpcs:enable
{
    public function __construct(
        private readonly RestClientInterface $restClient,
    ) {
    }
}
