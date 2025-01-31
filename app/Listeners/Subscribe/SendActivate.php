<?php

namespace App\Listeners\Subscribe;

use App\Events\Subscribe\Activate;
use App\Models\Mailing;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendActivate
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
    public function handle(Activate $event): void
    {
        $data = [];
        $data['owner_type'] = $event->model::class;
        $data['owner_id'] = $event->model->id;
        $data['user_id'] = $event->user->id;


        Mailing::updateOrCreate(
            $data,
            ['is_active'=> 1]
        );

    }
}
