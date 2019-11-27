<?php

namespace App\Providers;

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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Validator::extend('entry', function ($attribute, $value, $parameters, $validator) {
            if (\Dic::IsExistEntry($parameters[0], $value)) {
                return $value;
            } else {
                return false;
            }
        });
    }
}
