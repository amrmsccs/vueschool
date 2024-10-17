<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //Checking Using Application Timezone set in .env
        Carbon::macro('inApplicationTimezone', function() {
            return $this->tz(config('app.timezone_display'));
        });

        //User Inserted Timezone or use default timezone set in .env DSIPLAY_TIMEZONE
        Carbon::macro('inUserTimezone', function() {
            return $this->tz(auth()->user()?->timezone ?? config('app.timezone_display'));
        });
    }
}
