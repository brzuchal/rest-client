# RestClient Builder

The `RestClientBuilder` (accessible through `RestClient::builder()`) is a powerful tool provided by the RestClient package for building and configuring REST client requests in a fluent and customizable way. It allows you to set various options, such as the base URI, default headers, serialization context, and more, before creating a request. This documentation will guide you through the main features and usage of the RestClientBuilder.

## Basic Usage

To start using the `RestClientBuilder`, you need to obtain an instance of it using the `RestClient::builder()` method:

```php
use Brzuchal\RestClient\RestClient;

// Create a request builder using the builder() method
$builder = RestClient::builder();
```

Now you have a `$builder` instance that you can use to build your requests.

## Setting the Base URI

The base URI is the foundation of all your requests. You can set it using the `baseUrl()` method:

```php
$builder->baseUrl('https://api.example.com/');
```

## Setting Default Headers

You can set default headers that will be included in all requests created with this builder:

```php
$builder->defaultHeader('User-Agent', 'MyApp/1.0');
```

You can chain multiple `defaultHeader()` calls to set multiple headers.

## Setting Default Serialization Context

The RestClient package uses Symfony Serializer for handling request and response data. You can set a default serialization context using the `defaultContext()` method:

```php
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

$builder->defaultContext([
    DateTimeNormalizer::FORMAT_KEY => \DateTime::RFC3339,
]);
```

This allows you to control the serialization format, such as date and time formatting, for all requests created with this builder.

## Creating Requests

Once you have configured the builder, you can create requests of different types, such as GET, POST, PUT, DELETE, etc. For example, to create a GET request:

```php
$request = $builder->get('/todos/1');
```

You can chain the request creation methods directly after configuring the builder.

## Retrieving Responses

After creating a request, you can use the `retrieve()` method to send the request and retrieve the response:

```php
$response = $request->retrieve();
```
