<?php

namespace App\Providers;

use App\Models\ChatMessage;
use Illuminate\Support\Facades\View;
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
        View::composer('components.layouts.admin', function ($view): void {
            $view->with('unreadMessageCount', ChatMessage::whereNull('read_at')->count());
        });
    }
}
