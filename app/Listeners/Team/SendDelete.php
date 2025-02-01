<?php

namespace App\Listeners\Team;

use App\Events\Team\Delete as TeamDelete;
use App\Models\TeamJoin;
use App\Models\LogStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendDelete
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
    public function handle(TeamDelete $event): void
    {
        $teamId = $event->team->id;
        $idsDelete = TeamJoin::where('team_id', $teamId)->pluck('id')->toArray();
        LogStatus::where('owner_type','=',TeamJoin::class)->whereIn('owner_id',$idsDelete)->delete(); // удалить все статусы по группе
        TeamJoin::where('team_id', $teamId)->delete(); //Удалить все заявки на вступление по группе

    }
}
