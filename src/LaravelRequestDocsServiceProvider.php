<?php

namespace Rakutentech\LaravelRequestDocs;

use Illuminate\Support\Facades\Route;
use Rakutentech\LaravelRequestDocs\Commands\ExportRequestDocsCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelRequestDocsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-request-docs')
            ->hasConfigFile('request-docs')
            ->hasCommand(ExportRequestDocsCommand::class)
            // ->hasAssets()
            ->hasViews();
        // ->hasAssets();
        // publish resources/dist/_astro to public/
        $this->publishes([
            __DIR__.'/../resources/dist/_astro' => public_path('documentation/_astro'),
            __DIR__.'/../resources/dist/index.html' => public_path('documentation/index.html'),
        ], 'request-docs-assets');
    }

    public function packageBooted()
    {
        parent::packageBooted();
        if (!config('request-docs.enabled')) {
            return;
        }

        // URL from which the docs will be served.
        Route::get(config('request-docs.url'), [\Rakutentech\LaravelRequestDocs\Controllers\LaravelRequestDocsController::class, 'index'])
            ->name('request-docs.index')
            ->middleware(config('request-docs.middlewares'));

        // Following url for api and assets, donot change to config one.
        Route::get("documentation/api", [\Rakutentech\LaravelRequestDocs\Controllers\LaravelRequestDocsController::class, 'api'])
            ->name('request-docs.api')
            ->middleware(config('request-docs.middlewares'));

        Route::get("documentation/_astro/{slug}", [\Rakutentech\LaravelRequestDocs\Controllers\LaravelRequestDocsController::class, 'assets'])
            // where slug is either js or css
            ->where('slug', '.*js|.*css|.*png|.*jpg|.*jpeg|.*gif|.*svg|.*ico|.*woff|.*woff2|.*ttf|.*eot|.*otf|.*map')
            ->name('request-docs.assets')
            ->middleware(config('request-docs.middlewares'));
    }
}
