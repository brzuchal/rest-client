<?php

namespace Brzuchal\RestClient\Tests;

use Brzuchal\RestClient\RestClientInterface;
use Brzuchal\RestClient\RestClientServiceProvider;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase;

final class RestClientServiceProviderTest extends TestCase
{
    protected $loadEnvironmentVariables = false;

    /**
     * Define environment setup.
     *
     * @param Application $app
     * @return void
     */
    protected function resolveApplicationConfiguration($app): void
    {
        parent::resolveApplicationConfiguration($app);
        $app['config']->set('rest_client', [
            'clients' =>[
                'test' => ['base_uri' => 'https://example.com/todos'],
            ],
        ]);
    }

    /**
     * @psalm-return list<class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [RestClientServiceProvider::class];
    }

    public function testNamedRestClientIsBound(): void
    {
        $this->assertTrue(app()->bound('rest_client.test'));
        $this->assertInstanceOf(RestClientInterface::class, app('rest_client.test'));
    }
}
