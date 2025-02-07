<?php

namespace App\Listeners;

use App\Events\MaterialCreated;
use App\Mail\MaterialCreated as MailCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendMaterialCreated
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MaterialCreated $event): void
    {
        foreach ($event->material->category->subscriptions as $subscription) {
            Mail::to($subscription->user->email)->send(new MailCreated($event->material));
        }

    }
}
