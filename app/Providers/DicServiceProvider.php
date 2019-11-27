<?php

namespace App\Providers;

use App\Services\DicService;
use Illuminate\Support\ServiceProvider;

class DicServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('Dic', DicService::class);
    }
}
