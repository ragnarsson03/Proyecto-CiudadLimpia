<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Schema::defaultStringLength(191);
        
        if($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
