<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Console\Commands\MakeActionCommand;
use App\Console\Commands\MakeServiceCommand;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\Client;
use App\Models\Admin;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register custom commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeActionCommand::class,
                MakeServiceCommand::class,
            ]);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::morphMap([
            'client' => Client::class,
            'admin' => Admin::class,
        ]);
    }
}
