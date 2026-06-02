<?php

namespace App\Providers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

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
        if ($this->app->runningInConsole()) {
            return;
        }

        SymfonyRequest::setTrustedProxies(
            ['*'],
            SymfonyRequest::HEADER_X_FORWARDED_FOR |
            SymfonyRequest::HEADER_X_FORWARDED_HOST |
            SymfonyRequest::HEADER_X_FORWARDED_PROTO |
            SymfonyRequest::HEADER_X_FORWARDED_PORT |
            SymfonyRequest::HEADER_X_FORWARDED_PREFIX
        );

        if ($host = Request::server('HTTP_HOST')) {
            $scheme = Request::getScheme();
            if ($host !== 'localhost' && $host !== '127.0.0.1') {
                $scheme = 'https';
            }

            $appUrl = config('app.url') ?: env('APP_URL', '');
            if (! $appUrl || str_contains($appUrl, 'localhost')) {
                $appUrl = $scheme . '://' . $host;
            }

            if (str_starts_with($appUrl, 'http://')) {
                $appUrl = preg_replace('/^http:/i', 'https:', $appUrl);
            }

            config(['app.url' => rtrim($appUrl, '/'), 'app.asset_url' => rtrim($appUrl, '/')]);
            URL::forceRootUrl($appUrl);
        }

        URL::forceScheme('https');
    }
}
