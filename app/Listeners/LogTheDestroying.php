<?php

namespace App\Listeners;

use App\Events\DeleteForeverUser;
use Illuminate\Support\Facades\Auth;

class LogTheDestroying
{
    public function handle(DeleteForeverUser $event)
    {
        activity("user")->performedOn($event->user)->causedBy(Auth::user())->log("deleted forever");
    }
}
