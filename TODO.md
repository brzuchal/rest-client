# TODO

## URI Generation
* Evaluate the need for `UriFactory` and eventually propose a `Closure():string` for URI generation.

## Error Handlers
* Modify the `DefaultResponseExchange` to wrap `$response` interactions into a try-catch block if no error handler is registered.
* Consider whether default error handlers should be implemented or not, allowing the underlying HTTP client to throw exceptions.

```php
$this->errorHandlers = $errorHandlers ?? [
    400 => fn (ResponseInterface $r) => throw new RestClientResponseException($r),
    404 => fn () => null,
    410 => fn () => null,
];
```

## HTTP Client Setup
* Add a builder method for handling redirects in the HTTP client setup.

## RestClientFactory
* Implement body inclusion and argument resolving with the use of attributes.
* Ensure that `*Exchange` path properties properly resolve relative URI paths.

## Missing Tests

- **Symfony Integration Tests**
    - [ ] Test integration with Symfony Console Commands
    - [ ] Test integration with Symfony Controllers

- **Laravel Integration Tests**
    - [ ] Test integration with Laravel Artisan Commands
    - [ ] Test integration with Laravel Controllers

- **HTTP Client Follow Redirects Tests**
    - [ ] Test the follow redirects builder method for the HTTP client setup

- **Default Error Handlers Tests**
    - [ ] Consider adding default error handlers to RestClient
    - [ ] Test functionality of default error handlers

- **RestClientFactory Body Inclusion and Argument Resolving Tests**
    - [ ] Implement tests for including request bodies and resolving method arguments using attributes

- **RestClientFactory `*Exchange` Path Resolution Tests**
    - [ ] Test the proper resolution of relative URI paths for methods using `*Exchange` attributes
