<?php

namespace App\Listeners\Subscribe;

use App\Models\Mailing;
use App\Events\Subscribe\Disable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendDisable
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
    public function handle(Disable $event): void
    {
        $data = [];
        $data['owner_type'] = $event->model::class;
        $data['owner_id'] = $event->model->id;
        $data['user_id'] = $event->user->id;


        Mailing::updateOrCreate(
            $data,
            ['is_active'=> 0]
        );
    }
}
