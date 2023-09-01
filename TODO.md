## TODO

## URI generation
* evaluate the need for `UriFactory`, eventually propose a `Closure():string`

### Error handlers

* the  `DefaultResponseExchange` should wrap `$response` interaction into try-catch if no errorHandler registered
* default error handlers were added but then removed it is to consider whether they should be in place or not allowing the underlying HTTP client to throw.

```php
$this->errorHandlers = $errorHandlers ?? [
    400 => fn (ResponseInterface $r) => throw new RestClientResponseException($r),
    404 => fn () => null,
    410 => fn () => null,
];
```

### HTTP client setup

* add follow redirects builder method


### RestClientFactory

* implement body inclusion and argument resolving with use of attributes
* `*Exchange` path property should properly resolve relative URI path
