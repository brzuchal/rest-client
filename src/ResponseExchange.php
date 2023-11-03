<?php

declare(strict_types=1);

namespace Brzuchal\RestClient;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

interface ResponseExchange
{
    /** @param array<non-empty-string,mixed> $context */
    public function exchange(
        ResponseInterface $response,
        SerializerInterface $serializer,
        array $context = [],
    ): object|null;
}
