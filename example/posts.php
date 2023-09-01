<?php declare(strict_types=1);

namespace App\Posts;

use Brzuchal\RestClient\RestClient;

require_once __DIR__ . '/../vendor/autoload.php';

class Post
{
    public function __construct(
        public int $userId,
        public string $title,
        public string $body,
        public readonly int|null $id = null,
    ) {
    }
}

$restClient = RestClient::create('https://jsonplaceholder.typicode.com/');

// create post
$created = $restClient->post('/posts/')
    ->body(new Post(
        userId: 121,
        title: 'Custom title',
        body: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
    ))
    ->retrieve()
    ->toEntity(Post::class);
var_dump($created);

// modify post
$created->title = 'Changed title';
$updated = $restClient->put('/posts/{id}', ['id' => 1])
    ->body($created)
    ->retrieve()
    ->toEntity(Post::class);
var_dump($updated);

// fetch post
$entity = $restClient->get('/posts/{id}', ['id' => 1])
    ->retrieve()
    ->toEntity(Post::class);
var_dump($entity);
