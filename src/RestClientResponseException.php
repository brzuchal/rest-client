<?php

declare(strict_types=1);

namespace Brzuchal\RestClient;

use Exception;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class RestClientResponseException extends Exception
{
    public function __construct(
        public readonly ResponseInterface $response,
    ) {
        parent::__construct();
    }
}
