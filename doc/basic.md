# Basic Usage

The `RestClientInterface` in the RestClient package is a versatile and user-friendly 
tool for making HTTP requests in PHP applications. It offers a fluent and 
expressive API for building and sending HTTP requests, handling responses, 
and managing various aspects of the request process. This documentation page 
provides a comprehensive guide on how to use the `RestClientInterface` effectively.

## Creating a RestClient Instance

To begin using the `RestClientInterface`, you need to create an instance of it. 
You can do this by calling `RestClient::create()`:

```php
use Brzuchal\RestClient\RestClient;

$client = RestClient::create();
```

Once you have a `RestClientInterface` instance, you can start building and 
sending HTTP requests.

## Building HTTP Requests

The `RestClientInterface` offers methods for building various types of HTTP 
requests, including GET, HEAD, POST, PUT, PATCH, DELETE, and OPTIONS. 
Each of these methods returns a request builder object that allows you to 
customize the request before sending it.

| Intermediate Builder Method | `get()` | `head()` | `post()` | `put()` | `patch()` | `delete()` | `options()` |
|-----------------------------|---------|----------|----------|---------|-----------|------------|-------------|
| `contentType()`             | No      | No       | Yes      | Yes     | Yes       | Yes        | No          |
| `body()`                    | No      | No       | Yes      | Yes     | Yes       | Yes        | No          |
| `header()`                  | Yes     | Yes      | Yes      | Yes     | Yes       | Yes        | Yes         |
| `uri()`                     | Yes     | Yes      | Yes      | Yes     | Yes       | Yes        | Yes         |
| `accept()`                  | Yes     | Yes      | Yes      | Yes     | Yes       | Yes        | No          |
| `acceptCharset()`           | Yes     | Yes      | Yes      | Yes     | Yes       | Yes        | No          |
| `ifModifiedSince()`         | Yes     | Yes      | Yes      | Yes     | Yes       | Yes        | No          |
| `ifNoneMatch()`             | Yes     | Yes      | Yes      | Yes     | Yes       | Yes        | No          |

**Note**: "Yes" indicates that the `Intermediate Builder Method` can be used 
with the corresponding `RestClientInterface Method`, while "No" 
indicates it cannot be used.

## Customizing Requests

The `RestClientInterface` allows you to customize your requests in various ways.

### Setting the URI

You can specify the URI for your request using the `uri()` method:

```php
$request = $client->get()->uri('/api/resource');
```

Alternatively, you can use a URI factory closure with the `uriFactory()` method:

```php
$request = $client->get()->uriFactory(fn () => 'https://example.com/api/resource');
```

### Setting Request Headers

To add custom headers to your request, use the `header()` method:

```php
$request = $client->get()->header('Authorization', 'Bearer your-token');
```

### Setting Request Body

When sending POST, PUT, or PATCH requests, you can set the request body using 
the `body()` method. The body must be an object:

```php
$request = $client->post()->body(new Post(1, 'Documenting RestClient'));
```

### Setting Acceptable Media Types

Specify the list of acceptable media types (content types) using 
the `accept()` method:

```php
$request = $client->get()->accept('application/json', 'application/xml');
```

## Handling Responses

After sending a request, you can work with the response using the `ResponseSpec` 
class, which provides methods for processing the response.

### Retrieving a `ResponseSpec`

Use the `retrieve()` method to obtain a `ResponseSpec` instance for a given request:

```php
$responseSpec = $client->get('/api/resource')->retrieve();
```

### Converting Responses

The `ResponseSpec` class offers methods for converting response content:

#### To array

Convert the response content to an array:

```php
$responseData = $responseSpec->toArray();
```

#### To Entity class

Convert the response content to a specific entity object:

```php
$post = $responseSpec->toEntity(Post::class);
```

#### To iterable collection of Entity class

Convert the response content to a collection of entity objects:

```php
$comments = $responseSpec->toEntityCollection(Comment::class);
```

### Checking Status Codes

You can check the status code of the response using the `is2xxStatus()` method:

```php
$isSuccessful = $responseSpec->is2xxStatus();
```

## Error Handling

The `ResponseSpec` class allows you to define custom error handlers for specific 
status codes.

### Custom Error Handlers

You can provide a function to map specific error status codes to an error handler 
using the `onStatus()` method. This allows you to customize error handling based 
on specific status codes:

```php
$responseSpec->onStatus(404, fn ($response, $serializer) => null);
```

## Examples

Let's look at some examples of how to use the `RestClientInterface`:

```php
// Send a GET request
$responseSpec = $client->get('/api/posts')->retrieve();

// Convert the response to an array
$responseData = $responseSpec->toArray();

// Send a POST request with a JSON body
$responseSpec = $client->post('/api/posts')
    ->header('Content-Type', 'application/json')
    ->body(new Post(title: 'New Post'))
    ->retrieve();

// Convert the response to an entity
$post = $responseSpec->toEntity(Post::class);

// Check if the response is successful (2xx status code)
$isSuccessful = $responseSpec->is2xxStatus();
```

This comprehensive guide should help you get started with using 
the `RestClientInterface` effectively in your PHP applications.
