<?php declare(strict_types=1);

namespace App\TodoClient;

use Brzuchal\RestClient\Attributes\GetExchange;
use Brzuchal\RestClient\Attributes\GetCollectionExchange;
use Brzuchal\RestClient\Attributes\HttpExchange;
use Brzuchal\RestClient\Attributes\PostExchange;
use Brzuchal\RestClient\Attributes\RequestBody;
use Brzuchal\RestClient\RestClient;
use Brzuchal\RestClient\Factory\RestClientFactory;

require_once __DIR__ . '/../vendor/autoload.php';

final class Todo {
    public function __construct(
        public int $userId,
        public string $title,
        public bool $completed,
        public readonly int|null $id = null,
    ) {
    }
}

#[HttpExchange('https://jsonplaceholder.typicode.com/', accept: 'application/json')]
interface TodoClient {
    #[GetExchange('/todos/{id}')]
    public function fetch(int $id): Todo|null;

    #[PostExchange]
    public function create(#[RequestBody(groups: ['new'])] Todo $todo): Todo;

    #[GetCollectionExchange(entityType: Todo::class)]
    public function list(): iterable;
}

$client = (new RestClientFactory())->create(
    type: TodoClient::class,
    restClient: RestClient::create('https://jsonplaceholder.typicode.com/todos/'),
);

$todo = $client->fetch(1);
var_dump($todo);
