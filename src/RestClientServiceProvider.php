<?php

declare(strict_types=1);

namespace Brzuchal\RestClient;

use Illuminate\Support\ServiceProvider;

use function config;

/**
 * Laravel ServiceProvider.
 */
final class RestClientServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom($this->getConfigPath(), 'rest_client');
        foreach (config('rest_client.clients') as $name => $values) {
            $this->registerClient($name, $values);
        }
    }

    protected function getConfigPath(): string
    {
        return __DIR__ . '/../config/laravel.php';
    }

    /**
     * @param non-empty-string              $name
     * @param array<non-empty-string,mixed> $config
     */
    protected function registerClient(string $name, array $config): void
    {
        $id = 'rest_client.' . $name;
        $this->app->singleton($id, static fn () => RestClient::create($config['base_uri']));
    }
}
