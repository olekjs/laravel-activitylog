<?php

namespace Olekjs\ActivityLog;

use Illuminate\Support\ServiceProvider;

class ActivityLogServiceProvider extends ServiceProvider
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
        $this->publishes([
            __DIR__ . '/../migrations' => $this->app->databasePath() . '/migrations',
        ], 'migrations');

        $this->app->bind('activitylog', function () {
            return new Classes\ActivityLog();
        });
    }
}
