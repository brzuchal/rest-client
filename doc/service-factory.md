# Client Service Factory (Experimental)

<div class="alert alert-warning">
    <strong>Warning:</strong> The RestClientFactory is an experimental feature and may not be fully implemented or stable yet. Use it with caution and be prepared for potential changes in future releases.
</div>

The `RestClientFactory` is a powerful tool provided by the RestClient package that allows you to generate classes based on a specified interface. This can be incredibly useful when you want to create client classes for specific RESTful APIs, ensuring type safety and adherence to your API contracts.

## Usage

To use the `RestClientFactory`, follow these steps:

### 1. Define an Interface

Start by defining an interface that represents the contract for your RESTful API client. This interface should contain method signatures that correspond to the API endpoints and operations you need to perform. Additionally, use method-level attributes like `#[GetExchange()]`, `#[PostExchange()]`, `#[PutExchange()]`, and `#[DeleteExchange()]` to specify additional information like the URI, request method, request body, and query parameters.

For example, let's say you have an API for managing posts, and you want to create a client for it. You might define an interface like this:

```php
// src/Api/PostsApiClientInterface.php

namespace App\Api;

use Brzuchal\RestClient\Attributes\DeleteExchange;
use Brzuchal\RestClient\Attributes\GetExchange;
use Brzuchal\RestClient\Attributes\HttpExchange;
use Brzuchal\RestClient\Attributes\PostExchange;
use Brzuchal\RestClient\Attributes\PutExchange;
use Brzuchal\RestClient\Attributes\RequestBody;
use Brzuchal\RestClient\Attributes\RequestParam;

#[HttpExchange(
    baseUri: 'https://api.example.com/',
    defaultHeaders: ['User-Agent' => 'YourAppName'],
    accept: 'application/json',
    groups: ['default', 'post_details']
)]
interface PostsApiClientInterface
{
    /** @param positive-int $id */
    #[GetExchange('/{id}')]
    public function fetch(int $id): Post|null;

    #[PostExchange]
    public function create(
        #[RequestBody(['create'], contentType: 'application/json')]
        Post $post,
    ): Post;

    public function form(
        #[RequestParam('user_id', property: 'userId')]
        #[RequestParam(property: 'title')]
        #[RequestParam(property: 'body')]
        Post $post,
    ): Post;

    #[PostExchange('/{postId}/comments')]
    public function createComment(
        #[RequestBody(['create'])]
        #[RequestParam(property: 'postId')] 
        Comment $comment,
    ): Post;

    #[PutExchange('/{id}')]
    public function update(
        #[RequestBody(['update'])]
        #[RequestParam(property: 'id')]
        Post $post,
    ): Post;

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
```

In this interface, we've used method-level attributes to specify details such as the HTTP method, request body, and URI for each API endpoint.

### 2. Generate an API Client Service

Next, use the `RestClientFactory` to generate an API client service based on your interface. Instantiate the factory and call the `create()` method, passing the interface name as a parameter. This will generate a service that implements your interface and handles the API requests for you.

Here's an example of how to use the `RestClientFactory` to generate an API client service and use its methods:

```php
use Brzuchal\RestClient\RestClientFactory;

$factory = new RestClientFactory();
$client = $factory->create(PostsApiClientInterface::class);

// Now you can use $client to interact with your API
// For example:
$post = $client->fetch(1); // Fetch a post by ID
$posts = $client->list(); // Fetch a list of posts
$client->delete(1); // Delete a post by ID
```

#### How It Works Under the Hood

When you call `$factory->create()`, the `RestClientFactory` dynamically generates a class that implements the provided interface (`PostsApiClientInterface` in this example). This generated class acts as a proxy that intercepts calls to the interface methods and sends HTTP requests to the API endpoints defined in your interface.

Here's a breakdown of what happens under the hood:

1. **Dynamic Class Generation:** The factory creates a new class that implements the provided interface. This generated class uses an instance of `RestClient` which performs all the operations executions.

2. **Method Invocation:** When you call a method on the generated client service (`$client` in this example), it invokes the corresponding method on the dynamic class.

3. **API Requests:** The dynamic class maps the method call to an API request. It uses the attributes defined in your interface methods, such as `#[GetExchange()]`, `#[PostExchange()]`, etc., to determine the HTTP method, endpoint URI, request body, query parameters, and more.

4. **HTTP Requests:** The dynamic class sends HTTP requests to the API endpoint specified in your method attributes, using the configured HTTP client under the hood.

5. **Response Handling:** It receives the API response, deserializes it, and returns the result as specified in your interface method return types.

6. **Seamless Integration:** You can seamlessly use the generated client service (`$client`) in your application code, treating it as if it were an instance of your interface. This abstracts the complexity of making API requests and allows you to focus on your application's functionality.

By following these steps, you can easily generate an API client service for your RESTful API and use its methods to interact with the API endpoints defined in your interface, simplifying the process of working with external APIs in your application.
