# Custom Exchange

Creating custom classes implementing the `ResponseExchange` interface can be a powerful feature when working with RESTful services. It allows you to define custom logic for handling API responses and converting them into objects tailored to your application's needs. In this documentation, we'll explore some practical use cases for custom `ResponseExchange` classes and demonstrate how to create and use them with RestClient.

## Practical Use Cases

### Custom Deserialization

One common use case for custom `ResponseExchange` classes is to handle API responses that require custom deserialization logic. For example, if an API returns data in a non-standard format or with complex nested structures, you can create a custom exchange class to deserialize the response correctly.

### Conditional Handling

You can implement conditional logic in custom exchange classes to handle different API response scenarios. For instance, you might need to handle error responses differently from successful responses or adapt the deserialization process based on specific response headers.

### Data Transformation

Custom `ResponseExchange` classes can be used to transform API responses into application-specific data structures. This is particularly useful when the API response format does not align with your application's internal data models.

### Error Handling

Implementing custom exchange classes allows you to define how API errors are handled and how error responses are converted into meaningful exceptions or error messages within your application.

### Versioning

In situations where an API undergoes version changes, custom exchange classes can help manage backward compatibility by adjusting the response processing logic based on the API version.

## Creating Custom `ResponseExchange` Classes

To create a custom `ResponseExchange` class, follow these steps:

1. Create a new PHP class that implements the `ResponseExchange` interface:

```php
use Brzuchal\RestClient\ResponseExchange;
use Symfony\Component\Serializer\SerializerInterface;
use Psr\Http\Message\ResponseInterface;

class CustomExchange implements ResponseExchange
{
    public function exchange(
        ResponseInterface $response,
        SerializerInterface $serializer,
        array $context = []
    ): object|null {
        // Custom response processing logic here
    }
}
```

2. Implement the `exchange()` method, which receives the API response, a serializer instance, and an optional context array. Within this method, define your custom logic to deserialize, transform, or handle the API response.

### Using Custom `ResponseExchange` Classes

To use a custom `ResponseExchange` class with RestClient, you can pass an instance of your custom exchange class to the `exchange()` method when making API requests. Here's an example:

```php
use Brzuchal\RestClient\RestClient;

$customExchange = new CustomExchange();
$response = $client->get('/todos/1')
    ->exchange($customExchange);
```

In this example, we create an instance of `CustomExchange` and pass it to the `exchange()` method of the RestClient request. The `exchange()` method will use your custom exchange class to handle the API response based on the defined logic in the `exchange()` method of `CustomExchange`.

Custom `ResponseExchange` classes provide you with a way to extend RestClient's response handling capabilities and adapt them to the specific requirements of your RESTful services.
