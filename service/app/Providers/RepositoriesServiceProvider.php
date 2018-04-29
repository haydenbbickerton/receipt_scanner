<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Receipt;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\UserRepository;
use App\Repositories\EloquentUserRepository;
use App\Repositories\Contracts\ReceiptRepository;
use App\Repositories\EloquentReceiptRepository;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepository::class, function () {
            return new EloquentUserRepository(new User());
        });
        $this->app->bind(ReceiptRepository::class, function () {
            return new EloquentReceiptRepository(new Receipt());
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            UserRepository::class,
            ReceiptRepository::class,
        ];
    }
}