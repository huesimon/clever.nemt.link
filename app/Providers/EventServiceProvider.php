<?php

namespace App\Providers;

use App\Listeners\LastLoggedIn;
use App\Models\Charger;
use App\Models\LocationUser;
use App\Observers\ChargerObserver;
use App\Observers\LocationUserObserver;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Login::class => [
            LastLoggedIn::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Charger::observe(ChargerObserver::class);
        LocationUser::observe(LocationUserObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
