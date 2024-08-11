<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RomanNumeralServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('RomanNumeral', function () {
            return new \App\Helpers\RomanNumeralConverter;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
