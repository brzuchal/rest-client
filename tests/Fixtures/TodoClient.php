<?php declare(strict_types=1);

namespace Brzuchal\RestClient\Tests\Fixtures;

use Brzuchal\RestClient\Attributes\GetExchange;
use Brzuchal\RestClient\Attributes\GetCollectionExchange;

interface TodoClient {
    #[GetExchange('/todos/{id}')]
    public function fetch(int $id): Todo|null;

    #[GetCollectionExchange(Todo::class, '/todos/')]
    public function list(): iterable;
}

