<?php

namespace App\Listeners;

use App\Events\NewUserSignup;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewUserSignupNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewUserSignup  $event
     * @return void
     */
    public function handle(NewUserSignup $event)
    {
        //
    }
}
