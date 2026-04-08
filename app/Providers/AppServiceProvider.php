<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\RoleMiddleware;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // daftarkan alias middleware
        $this->app['router']->aliasMiddleware('role', RoleMiddleware::class);

        // parent::boot(); // ini biasanya tidak perlu di AppServiceProvider
    }
}
