<?php

declare(strict_types=1);

namespace Brzuchal\RestClient\Factory;

use Brzuchal\RestClient\RestClientInterface;

trait RestClientTrait
{
    public function __construct(
        private RestClientInterface $restClient,
    ) {
    }
}
