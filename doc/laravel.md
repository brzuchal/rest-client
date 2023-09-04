# Integrating with Laravel

The RestClient package can be seamlessly integrated into a Laravel application to simplify interaction with RESTful APIs. This documentation will guide you through integrating the RestClient package into your Laravel application and demonstrate its usage in both Laravel Artisan Commands and Controllers.

## Installation

Before integrating RestClient with your Laravel application, ensure that you have installed the RestClient package using Composer. Refer to the [README.md](../README.md) for installation instructions.

## Configuration

To configure RestClient for your Laravel application, follow these steps:

### 1. Add ServiceProvider

Open the `config/app.php` file in your Laravel project and add the `RestClientServiceProvider` to the list of service providers:

```php
// config/app.php

'providers' => [
    // ...
    Brzuchal\RestClient\RestClientServiceProvider::class,
],
```

### 2. Create Configuration File

Create a configuration file (e.g., `rest_client.php`) in the `config` directory of your Laravel project:

```php
// config/rest_client.php

return [
    'clients' => [
        'default' => [
            'base_uri' => 'https://api.example.com/', // Specify the base URI of your API
            'default_headers' => [
                'User-Agent' => 'YourAppName', // Set your application's User-Agent header
            ],
        ],
    ],
];
```

In this configuration file:

- We define a RestClient client named `default` with a specified base URI.
- We set a default User-Agent header, which is optional but recommended.

Feel free to add more client configurations as needed for different APIs.

## Laravel Artisan Command Example

Suppose you want to create a Laravel Artisan Command that uses RestClient to fetch data from an API. Here's an example:

```php
// app/Console/Commands/FetchPostsCommand.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Brzuchal\RestClient\RestClientInterface;

class FetchPostsCommand extends Command
{
    protected $signature = 'app:fetch-posts';
    protected $description = 'Fetch posts from an API';

    public function handle()
    {
        // Use app() to resolve the RestClient service
        $restClient = app('rest_client.default');
        
        // Use $restClient to make API requests and handle responses
        $response = $restClient->get('/posts')->retrieve();

        // Process the API response and display data
        $data = $response->toArray();
        $this->line(json_encode($data, JSON_PRETTY_PRINT));
    }
}
```

In this example, we create a Laravel Artisan Command named `FetchPostsCommand`. We use the `app('rest_client.default')` function to explicitly resolve the RestClient service.

## Laravel Controller Example

If you prefer to use RestClient within a Laravel Controller, you can do so with ease. Here's an example of a Laravel Controller that fetches data from an API:

```php
// app/Http/Controllers/ApiController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Brzuchal\RestClient\RestClientInterface;

class ApiController extends Controller
{
    public function fetchData(Request $request)
    {
        // Use app() to resolve the RestClient service
        $restClient = app('rest_client.default');
        
        // Use $restClient to make API requests and handle responses
        $response = $restClient->get('/api/data')->retrieve();

        // Process the API response and return it as a JSON response
        $data = $response->toArray();

        return response()->json($data);
    }
}
```

In this example, we inject the RestClient instance into the controller's action using `app('rest_client.default')`. We then use it to fetch data from the API in the `fetchData` action and return the data as a JSON response.

You can adapt these examples to fit your specific use case and interact with various RESTful APIs as required.

