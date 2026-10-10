<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Wires config/roles.php (+ any DB override) into the Gate, so both
        // Blade's @can directives and $user->can('x') in controllers work.
        // See User::hasPermission() for the actual rule.
        foreach (['homepage', 'media', 'content', 'ads', 'moderate', 'members', 'notify', 'analytics'] as $ability) {
            Gate::define($ability, fn ($user) => $user->hasPermission($ability));
        }
    }
}
