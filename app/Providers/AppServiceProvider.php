<?php

namespace App\Providers;

use App\Core\Contracts\Link\LinkData;
use App\Core\Contracts\Link\LinkRepository;
use App\Core\Implementations\Link\EloquentLinkRepository;
use App\Core\Implementations\Link\Link;
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
        $this->app->bind(LinkRepository::class, EloquentLinkRepository::class);
        $this->app->bind(LinkData::class, Link::class);
    }
}
