<?php declare(strict_types=1);

namespace App\PostsBuilder;

use Brzuchal\RestClient\RestClient;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

require_once __DIR__ . '/../vendor/autoload.php';

class Post
{
    public function __construct(
        public int $userId = 121,
        public string $title = 'Custom title',
        public string $body = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        public readonly int|null $id = null,
    ) {
    }
}

$restClient = RestClient::builder()
    ->baseUrl('https://jsonplaceholder.typicode.com/')
    ->defaultContext([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s'])
    ->defaultHeader('Authorisation', 'Bearer YWxhbWFrb3Rh')
    ->build();

// create post
$created = $restClient->post('/posts/')
    ->body(new Post())
    ->retrieve()
    ->toEntity(Post::class);
var_dump($created);

// modify post
$created->body = 'Changed body';
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
