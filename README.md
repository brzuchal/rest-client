# RestClient

The RestClient package is a PHP library that simplifies working with RESTful APIs. It provides an easy way to create and configure HTTP requests, handle responses, and convert JSON data into PHP objects. This documentation will guide you through the main features and usage of the RestClient package.

Whether you're building web services or powerful API clients in your applications, the RestClient package streamlines the process of interacting with RESTful APIs in PHP. It allows you to focus on your application's functionality rather than the intricacies of making HTTP requests and processing responses.

## Installation

You can install the RestClient package via Composer:

```shell
composer require brzuchal/rest-client
```

## Documentation

* [RestClient Builder](doc/builder.md)
* [Using Entity Classes](doc/entity.md)
* [Custom Response Handling](doc/exchange.md)
* [Integrating with Symfony](doc/symfony.md)
* [Integrating with Laravel](doc/laravel.md)
* [Client Service Factory (Experimental)](doc/service-factory.md)

## Getting Started

To get started with the RestClient package, create a `RestClient` instance. You can use the `RestClient::create` method to do this:

```php
use Brzuchal\RestClient\RestClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

$baseUri = 'https://api.example.com/';
$httpClient = HttpClientInterface::class;
$restClient = RestClient::create($baseUri, $httpClient);
```

Now you have a `RestClient` instance ready to make HTTP requests.

## Making Requests

The RestClient package allows you to create various types of HTTP requests, such as GET, POST, PUT, DELETE, etc. You can use the RestClient instance to create request objects for these methods.

### GET Request

```php
$getRequest = $restClient->get('/todos/1');
```

### POST Request

```php
$postRequest = $restClient->post('/todos');
```

### PUT Request

```php
$putRequest = $restClient->put('/todos/1');
```

### DELETE Request

```php
$deleteRequest = $restClient->delete('/todos/1');
```

### Handling Responses

```php
$response = $getRequest->retrieve();
$data = $response->toArray();
$todo = $response->toEntity(Todo::class);
```

## Error Handling

```php
$response = $restClient->get('/todos/1')->retrieve();

$response->onStatus(404, function ($response) {
    throw new NotFoundException('Resource not found');
});

$response->onStatus(500, function ($response) {
    throw new ServerErrorException('Server error');
});
```

## Symfony Framework

### Configuration

To use the RestClient package in a Symfony application, follow these steps:

1. Register the bundle in your Symfony application by adding the `RestClientBundle` to the `config/bundles.php` file:

   ```php
   // config/bundles.php

   return [
       // ...
       Brzuchal\RestClient\RestClientBundle::class => ['all' => true],
   ];
   ```

2. By default, the RestClientBundle uses Symfony configuration to define REST client services. Create a configuration file (e.g., `rest_client.yaml`) in the `config/packages` directory of your Symfony project. Here's an example configuration:

   ```yaml
   # config/packages/rest_client.yaml

   rest_client:
       clients:
           my_rest_client:
               base_uri: 'https://api.example.com/'
   ```

   In this configuration, we define a `my_rest_client` service with a base URI of `https://api.example.com/`. You can add more client configurations as needed.

### Example Symfony Controller

Here's an example Symfony controller that uses the RestClient package to make API requests:

```php
use Brzuchal\RestClient\RestClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TodoController extends AbstractController
{
    public function index(HttpClientInterface $httpClient): JsonResponse
    {
        $restClient = RestClient::create('https://jsonplaceholder.typicode.com', $httpClient);

        $response = $restClient->get('/todos');
        $data = $response->toArray();

        return $this->json($data);
    }
}
```

## Laravel Framework

### Configuration

To use the RestClient package in a Laravel application, follow these steps:

1. Create a configuration file for the RestClient package using the following Artisan command:

   ```shell
   php artisan vendor:publish --tag=config
   ```

   This command will generate a `rest_client.php` configuration file in the `config` directory of your Laravel project.

   > **NOTE!**: Laravel 8 and later versions should automatically discover the package. For older Laravel versions, you may need to register the service provider manually.

2. The configuration for the RestClient package in Laravel is similar to Symfony. Here's an example configuration file (`config/rest_client.php`):

   ```php
   return [
       'clients' => [
           'my_rest_client' => [
               'base_uri' => 'https://api.example.com/',
           ],
       ],
   ];
   ```

   In this configuration, we define a `my_rest_client` service with a base URI of `https://api.example.com/`. You can add more client configurations as needed.

### Example Laravel Controller

Here's an example Laravel controller that uses the RestClient package to make API requests:

```php
namespace App\Http\Controllers;

use Brzuchal\RestClient\RestClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TodoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $restClient = RestClient::create('https://jsonplaceholder.typicode.com', Http::class);

        $response = $restClient->get('/todos');
        $data = $response->toArray();

        return response()->json($data);
    }
}
```

## License

The RestClient package is open-source software licensed under the MIT License. See the [LICENSE](LICENSE) file for more information.
