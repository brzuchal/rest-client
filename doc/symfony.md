# Integrating with Symfony

The RestClient package can be seamlessly integrated into a Symfony application to simplify interaction with RESTful APIs. This documentation will guide you through integrating the RestClient package into your Symfony application and demonstrate its usage in both Symfony Console Commands and Controllers.

## Installation

Before integrating RestClient with your Symfony application, ensure that you have installed the RestClient package using Composer. Refer to the [README.md](../README.md) for installation instructions.

## Configuration

To configure RestClient for your Symfony application, follow these steps:

### 1. Add RestClientBundle

Open the `config/bundles.php` file in your Symfony project and add the `RestClientBundle`:

```php
// config/bundles.php

return [
    // ...
    Brzuchal\RestClient\RestClientBundle::class => ['all' => true],
];
```

### 2. Create Configuration File

Create a configuration file (e.g., `rest_client.yaml`) in the `config/packages` directory of your Symfony project:

```yaml
# config/packages/rest_client.yaml

rest_client:
    clients:
        default:
            base_uri: 'https://api.example.com/' # Specify the base URI of your API
            default_headers:
                User-Agent: 'YourAppName' # Set your application's User-Agent header
```

In this configuration file:

- We define a RestClient client named `default` with a specified base URI.
- We set a default User-Agent header, which is optional but recommended.

Feel free to add more client configurations as needed for different APIs.

## Console Command Example

Suppose you want to create a Symfony Console Command that uses RestClient to fetch data from an API. Here's an example:

```php
// src/Command/FetchPostsCommand.php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Brzuchal\RestClient\RestClientInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class FetchPostsCommand extends Command
{
    public function __construct(
        #[Autowire(service: 'rest_client.default')]
        private RestClientInterface $restClient,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:fetch-posts')
            ->setDescription('Fetch posts from an API');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Use $this->restClient to make API requests and handle responses
        $response = $this->restClient->get('/posts')->retrieve();

        // Process the API response and display data
        $data = $response->toArray();
        $output->writeln(json_encode($data, JSON_PRETTY_PRINT));

        return Command::SUCCESS;
    }
}
```

In this example, we create a Symfony Console Command named `FetchPostsCommand`. The `RestClientInterface` service is injected via constructor autowiring using the `#[Autowire]` attribute.

## Controller Example

If you prefer to use RestClient within a Symfony Controller, you can do so with ease. Here's an example of a Symfony Controller that fetches data from an API:

```php
// src/Controller/ApiController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Brzuchal\RestClient\RestClientInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class ApiController extends AbstractController
{
    public function __construct(
        #[Autowire(service: 'rest_client.default')]
        private RestClientInterface $restClient,
    ) {
    }

    #[Route("/fetch-posts", name="fetch_posts")]
    public function fetchPosts(): Response
    {
        // Use $this->restClient to make API requests and handle responses
        $response = $this->restClient->get('/posts')->retrieve();

        // Process the API response and return it as a JSON response
        $data = $response->toArray();

        return $this->json($data);
    }
}
```

Suppose you want to display data retrieved from a RESTful API in a Symfony Controller. Below is an example of a controller that fetches and displays data:

```php
// src/Controller/ApiController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Brzuchal\RestClient\RestClientInterface;

class ApiController extends AbstractController
{
    public function __construct(
        #[Autowire(service: 'rest_client.default')]
        private RestClientInterface $restClient,
    ) {
    }

    #[Route("/fetch-data", name="fetch_data")]
    public function fetchData(): Response
    {
        $response = $this->restClient->get('/api/data')->retrieve();
        $data = $response->toArray();

        // Use the fetched data in your application (e.g., pass it to a template)
        // ...

        return $this->render('api/fetched_data.html.twig', [
            'data' => $data,
        ]);
    }
}
```

In this example, we inject the RestClient instance into the controller's constructor and use it to fetch data from the API in the `fetchData` action. The fetched data can then be used in your application as needed.

You can adapt this example to fit your specific use case and interact with various RESTful APIs as required.
