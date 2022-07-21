<?php

namespace App\Providers;

use App\Http\Responses\Contracts\LoginResponse;
use App\Http\Responses\Contracts\PasswordUpdateResponse;
use App\Http\Responses\Contracts\RegisterResponse;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->instance(
            LoginResponse::class,
            new \App\Http\Responses\LoginResponse()
        );

        $this->app->instance(
            RegisterResponse::class,
            new \App\Http\Responses\RegisterResponse()
        );

        $this->app->instance(
            PasswordUpdateResponse::class,
            new \App\Http\Responses\PasswordUpdateResponse()
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
