<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmYourEmailAddress;
use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailConfirmationRequest
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
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        Mail::to($event->user)->send(new ConfirmYourEmailAddress($event->user));
    }
}
