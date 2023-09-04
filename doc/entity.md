# Using Entity Classes

The RestClient package simplifies the process of working with RESTful APIs and allows you to map API responses to PHP entity classes. This documentation will guide you through the process of using entity classes with RestClient, starting from defining the entity classes to mapping API responses.

## Defining Entity Classes

Entity classes represent the structure of data retrieved from the API. Each entity class should map to a specific type of resource returned by the API. Here's an example of an entity class for a "Todo" resource:

```php
class Todo
{
    public function __construct(
        public int $id,
        public int $userId,
        public string $title,
        public bool $completed,
    ) {}
}
```

In this example, the `Todo` class represents the structure of a "Todo" resource, including its properties such as `id`, `userId`, `title`, and `completed`.

## Intermediate Response Builders

Before mapping API responses to entity classes, RestClient provides intermediate response builders for additional configuration. These builders allow you to set options like serialization contexts and more. Let's look at an example:

```php
$response = $client->get('/todos/1')
    ->requestHeaders([
        'Accept' => 'application/json',
    ])
    ->serializationContext([
        'groups' => 'todo_details',
    ])
    ->retrieve();

$todo = $response->toEntity(Todo::class);
```

In this example, we create a `RestClientInterface` instance (`$client`) and set request headers and a serialization context using the response builder methods. These intermediate builders enable you to fine-tune the request and response configuration.

## Mapping API Responses to Entity Classes

Once you have defined your entity class and configured the response builder, you can map API responses to your entity class using the `toEntity()` or `toEntityCollection()` methods. Here's how to do it:

### Mapping a Single Entity

To map a single entity from the API response to your entity class, use the `toEntity()` method:

```php
$todo = $response->toEntity(Todo::class);
```

In this example, we retrieve the response using the `RestClientInterface` instance (`$client`) and then map it to the `Todo` entity class.

### Mapping Multiple Entities

To map multiple entities from the API response to your entity class, use the `toEntityCollection()` method:

```php
$todoCollection = $response->toEntityCollection(Todo::class);
```

In this example, we retrieve the response using the `RestClientInterface` instance (`$client`) and then map it to a collection of `Todo` entities.

## Retrieving Data as an Array

In some cases, you may want to retrieve API data as an array instead of mapping it to an entity class. You can use the `toArray()` method for this purpose:

```php
$data = $response->toArray();
```

The `toArray()` method returns the API data as an array.

## Customizing Serialization Context

You can customize the serialization context for entity mapping by using the `serializationContext()` method in the response builder. This allows you to specify groups, version, or other serialization options for fine-grained control over data mapping.

## Intermediate Builder Methods

The intermediate builder methods provided by RestClient allow you to customize various aspects of your API requests before sending them. Below are descriptions of these methods with more meaningful headers:

### Setting Request URI

Use the `uri(string $uri)` method to set the URI for the HTTP request. This method specifies the path or endpoint of the API you want to access. Example:

```php
$response = $client->get('/todos/1')
    ->uri('/new-endpoint') // Set a new URI
    ->retrieve();
```

### Custom URI Factory

Use the `uriFactory(UriFactoryInterface $uriFactory)` method to set a custom `UriFactoryInterface` for building URIs. This method is useful when you want to use a custom URI factory to create URIs. Example:

```php
$customUriFactory = new CustomUriFactory();
$response = $client->get('/todos/1')
    ->uriFactory($customUriFactory) // Set a custom URI factory
    ->retrieve();
```

### Requesting Specific Media Type

Use the `accept(string $mediaType)` method to set the `Accept` header for the request. This method specifies the media type that the server should use for the response. Example:

```php
$response = $client->get('/todos/1')
    ->accept('application/json') // Set the 'Accept' header to request a JSON response
    ->retrieve();
```

### Controlling Character Encoding

Use the `acceptCharset(string $charset)` method to set the `Accept-Charset` header for the request. This method specifies the character encoding that the server should use for the response. Example:

```php
$response = $client->get('/todos/1')
    ->acceptCharset('utf-8') // Set the character encoding to UTF-8
    ->retrieve();
```

### Handling Conditional GET Requests

Use the `ifModifiedSince(DateTimeInterface $dateTime)` method to set the `If-Modified-Since` header for the request. This method allows you to specify a date and time, and the server will only return the resource if it has been modified since that date. Example:

```php
$dateTime = new DateTime('2023-01-01');
$response = $client->get('/todos/1')
    ->ifModifiedSince($dateTime) // Set the 'If-Modified-Since' header
    ->retrieve();
```

### Efficient Cache Control with Etags

Use the `ifNoneMatch(string $etag)` method to set the `If-None-Match` header for the request. This method specifies an entity tag (ETag), and the server will only return the resource if it does not match the ETag provided. Example:

```php
$response = $client->get('/todos/1')
    ->ifNoneMatch('123456') // Set the 'If-None-Match' header
    ->retrieve();
```

### Adding Custom Headers

Use the `header(string $name, string $value)` method to set a custom header for the request. This method allows you to include additional headers in the HTTP request. Example:

```php
$response = $client->get('/todos/1')
    ->header('X-Custom-Header', 'Custom-Value') // Set a custom header
    ->retrieve();
```

### Customizing Request Exchange

Use the `exchange(ExchangeInterface $exchange)` method to set a custom exchange for the request. This method allows you to use a custom exchange for handling the request and response. Example:

```php
$customExchange = new CustomExchange();
$response = $client->get('/todos/1')
    ->exchange($customExchange) // Set a custom exchange
    ->retrieve();
```

### Setting Request Content Type

Use the `contentType(string $contentType)` method to set the `Content-Type` header for requests with a request body. This method specifies the media type of the request body. Example:

```php
$response = $client->post('/todos')
    ->contentType('application/json') // Set the 'Content-Type' header for JSON request body
    ->body($jsonBody)
    ->retrieve();
```

### Attaching a Request Body

Use the `body(object $body)` method to set the request body for HTTP requests. This method allows you to attach an object as the request body, and it applies Symfony Serializer's `serialize()` method with a combined serialization context, including groups and a custom date format.

Example:

```php
// Create a Todo object
$todo = new Todo(
    id: 1,
    userId: 1,
    title: 'delectus aut autem',
    completed: false
);

// Set the request body with the Todo object
$response = $client->post('/todos')
    ->contentType('application/json')
    ->body($todo) // Attach the Todo object as the request body
    ->retrieve();
```

In this example, we create a `Todo` object and set it as the request body. The `body()` method automatically serializes the `Todo` object using Symfony Serializer with the specified serialization context, including groups and a custom date format.

These intermediate builder methods provide flexibility and customization options when making API requests with RestClient, allowing you to tailor your requests to specific API requirements.
