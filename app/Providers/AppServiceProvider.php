<?php

namespace App\Providers;

use App\Events\TraceEventCreated;
use App\Listeners\CreateTraceAlert;
use App\Listeners\DetectTraceabilityAnomaly;
use App\Listeners\UpdateLotStatus;
use App\Models\Lot;
use App\Policies\TraceabilityPolicy;
use Illuminate\Support\Facades\Gate;
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
        Gate::policy(Lot::class, TraceabilityPolicy::class);

        $this->app['events']->listen(TraceEventCreated::class, [UpdateLotStatus::class, 'handle']);
        $this->app['events']->listen(TraceEventCreated::class, [DetectTraceabilityAnomaly::class, 'handle']);
        $this->app['events']->listen(TraceEventCreated::class, [CreateTraceAlert::class, 'handle']);
    }
}
