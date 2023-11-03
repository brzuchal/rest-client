<?php

declare(strict_types=1);

namespace Brzuchal\RestClient;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

interface ResponseExchange
{
    public function exchange(
        ResponseInterface $response,
        SerializerInterface $serializer,
        array $context = [],
    ): object|null;
}
