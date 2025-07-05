<?php

namespace App\Providers;

use App\Models\Task;
use App\Policies\TaskPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */


    // This links a model to a associated policy
     protected $policies = [
    Task::class => TaskPolicy::class,
];
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */

     // This method will be automatically called when the application starts
public function boot(): void
{
    $this->registerPolicies(); // <- dit zorgt dat de policy actief is
}
}
