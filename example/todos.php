<?php declare(strict_types=1);

namespace App\Todos;

use Brzuchal\RestClient\RestClient;
use DateTimeImmutable;

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

$todo = RestClient::create('https://jsonplaceholder.typicode.com/')
    ->get('/todos/{id}', ['id' => 1])
    ->retrieve()
    ->toEntity(Todo::class);

var_dump($todo);

###

$since = new DateTimeImmutable('2022-07-23');
$todo = RestClient::create('https://jsonplaceholder.typicode.com/')
    ->get('/todos/{id}', ['id' => 1])
    ->accept('application/json')
    ->acceptCharset('utf-8')
    ->ifModifiedSince($since)
    ->header('Authorisation', 'Bearer YWxhbWFrb3Rh')
    ->retrieve()
    ->onStatus(500, fn () => null)
    ->toEntity(Todo::class);

var_dump($todo);
