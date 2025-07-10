<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class EloquentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading(! app()->isProduction());
        Relation::requireMorphMap();
        Relation::enforceMorphMap([
            'contractor' => \App\Models\Contractor::class,
            'employee'   => \App\Models\Employee::class,
            'team'       => \App\Models\Team::class,
            'user'       => \App\Models\User::class,
            'client'     => \App\Models\Client::class,
        ]);
    }
}
