<?php

namespace App\Providers;

use App\Services\DinstarGatewayClient;
use App\Services\SmsGatewayClient;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SmsGatewayClient::class, DinstarGatewayClient::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        RateLimiter::for('sms-send', function () {
            $limit = (int) config('sms.rate_limit_per_minute', 60);

            return Limit::perMinute(max(1, $limit));
        });
    }
}
