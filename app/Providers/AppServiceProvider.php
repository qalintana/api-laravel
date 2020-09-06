<?php

namespace App\Providers;

use App\Category;
use App\Observers\CategoryObserver;
use App\Observers\RealStateObserver;
use App\RealState;
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
        RealState::observe(RealStateObserver::class);
        Category::observe(CategoryObserver::class);
    }
}
