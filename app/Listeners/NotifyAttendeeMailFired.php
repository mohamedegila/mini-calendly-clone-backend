<?php

namespace App\Listeners;

use App\Events\NotifyAttendeeMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\NotifyAttendeesMail;
use Mail;

class NotifyAttendeeMailFired
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
     * @param  \App\Events\NotifyAttendeeMail  $event
     * @return void
     */
    public function handle(NotifyAttendeeMail $event)
    {
        $users   = $event->users;
        $details = $event->details;

        foreach($users as $user){
            \Mail::to($user)

            ->send(new NotifyAttendeesMail($details));

        }
    }
}
