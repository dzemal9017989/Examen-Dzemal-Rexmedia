<?php

namespace App\Providers;

use App\Models\Taak;
use App\Policies\TaakPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */

     protected $policies = [
    Taak::class => TaakPolicy::class,
];
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
public function boot(): void
{
    $this->registerPolicies(); // <- dit zorgt dat de policy actief is
}
}
