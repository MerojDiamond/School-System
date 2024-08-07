<?php

namespace App\Providers;

use App\Events\DeleteForeverUser;
use App\Listeners\LogTheDestroying;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];
    public function boot()
    {
        Event::listen(
            DeleteForeverUser::class,
            [LogTheDestroying::class, "handle"]
        );
    }
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
