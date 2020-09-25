<?php


namespace App\Providers;


use App\Services\AutoService;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider;

class AutoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->when(AutoService::class)
            ->needs(ClientInterface::class)
            ->give(function () {
                return new Client([
                    'base_uri' => config('services.auto.base_uri'),
                    'timeout' => config('services.auto.timeout'),
                ]);
            });
    }
}
