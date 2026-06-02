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
        if (! app()->environment('local') && ! $this->app->runningInConsole()) {
            SymfonyRequest::setTrustedProxies(
                ['*'],
                SymfonyRequest::HEADER_X_FORWARDED_FOR |
                SymfonyRequest::HEADER_X_FORWARDED_HOST |
                SymfonyRequest::HEADER_X_FORWARDED_PROTO |
                SymfonyRequest::HEADER_X_FORWARDED_PORT |
                SymfonyRequest::HEADER_X_FORWARDED_PREFIX
            );

            URL::forceScheme('https');

            if (Request::server('HTTP_HOST')) {
                $appUrl = Request::getSchemeAndHttpHost();
                config(['app.url' => $appUrl]);
                URL::forceRootUrl($appUrl);
            }
        }
    }
}
