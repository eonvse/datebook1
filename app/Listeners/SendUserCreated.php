<?php

namespace App\Listeners;

use App\Events\UserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Models\Team;

class SendUserCreated
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
    public function handle(UserCreated $event): void
    {
        //Добавление в группу по умолчанию


        $defaultTeam = Team::where('name','=','Public')->first();
        $event->user->teams()->attach($defaultTeam);

        $event->user->current_team_id = $defaultTeam->id;
        $event->user->save();

        //Назначение роль по умолчанию TODO
    }
}
