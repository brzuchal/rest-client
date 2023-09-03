<?php declare(strict_types=1);

namespace App\PostClient;

use Brzuchal\RestClient\Attributes\AsRestClient;
use Brzuchal\RestClient\Attributes\DeleteExchange;
use Brzuchal\RestClient\Attributes\GetExchange;
use Brzuchal\RestClient\Attributes\GetCollectionExchange;
use Brzuchal\RestClient\Attributes\HttpExchange;
use Brzuchal\RestClient\Attributes\RequestParam;
use Brzuchal\RestClient\Attributes\UriParam;
use Brzuchal\RestClient\Attributes\PostExchange;
use Brzuchal\RestClient\Attributes\PutExchange;
use Brzuchal\RestClient\Attributes\RequestBody;
use Brzuchal\RestClient\RestClient;
use Brzuchal\RestClient\Factory\RestClientFactory;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

require_once __DIR__ . '/../vendor/autoload.php';

final class Post
{
    /**
     * @param positive-int|null $id
     * @param positive-int $userId
     */
    #[Groups(['create', 'update', 'view'])]
    public function __construct(
        #[Assert\Positive]
        public int $userId = 121,
        #[Assert\NotBlank]
        public string $title = 'Custom title',
        public string $body = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        #[Groups(['update', 'view'])]
        public readonly int|null $id = null,
    ) {
    }
}
final class Comment
{
    /**
     * @param positive-int|null $id
     * @param positive-int $postId
     */
    #[Groups(['create', 'update', 'view'])]
    public function __construct(
        #[Assert\Positive]
        public readonly int $postId,
        #[Assert\NotBlank]
        public string $name,
        #[Assert\Email]
        public string $email,
        public string $body,
        #[Groups(['update', 'view'])]
        public readonly int|null $id = null,
    ) {
    }
}
#[AsRestClient,HttpExchange('https://jsonplaceholder.typicode.com/posts/', groups: ['view'], accept: 'application/json')]
interface PostClient {
    /** @param positive-int $id */
    #[GetExchange('{id}')]
    public function fetch(int $id): Post|null;

    #[PostExchange]
    public function create(
        #[RequestBody(['create'], contentType: 'application/json')]
        Post $post,
    ): Post;

    #[PostExchange]
    public function form(
        #[RequestParam('user_id', property: 'userId'),RequestParam(property: 'title'),RequestParam(property: 'body')]
        Post $post,
    ): Post;

    #[PostExchange('/{postId}/comments')]
    public function createComment(#[RequestBody(['create']),UriParam(property: 'postId')] Comment $comment): Post;

    #[PutExchange('/{id}')]
    public function update(#[RequestBody(['update']),UriParam(property: 'id')] Post $post): Post;

    /**
     * @return iterable<Post>
     */
    #[GetCollectionExchange(Post::class)]
    public function list(): iterable;

    /**
     * @param positive-int $id
     * @return iterable<Comment>
     */
    #[GetCollectionExchange(Comment::class, uri: '/{id}')]
    public function listComments(int $id): iterable;

    /** @param positive-int $id */
    #[DeleteExchange('/{id}')]
    public function delete(int $id): void;
}

$client = @(new RestClientFactory())->create(
    type: PostClient::class,
    restClient: RestClient::create('https://jsonplaceholder.typicode.com/posts/'),
);

$post = $client->fetch(1);
var_dump($post);

###

// TODO: not supported yet, requires implementing in RestClientFactory with argument resolving attributes
//$comment = $client->createComment(new Comment($post->id, 'Kermit', 'kermit@example.com', 'Lorem'));
//var_dump($comment);
