<?php

namespace App\Listeners\Team;

use App\Events\Team\JoinCancel;
use App\Models\LogStatus;
use App\Models\Status;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendJoinCancel
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
    public function handle(JoinCancel $event): void
    {
        $team_join = $event->teamJoin;

        //запись в журнал статуса успешного подтверждения запроса на вступление в группу
        $statusCanceled =
            Status::where('name','=','cancelled')->where('model','=',$team_join::class)->get()->first() ??
                Status::create(['name'=>'cancelled','model'=>$team_join::class]);

        LogStatus::create([
            'owner_type' => $team_join::class,
            'owner_id' => $team_join->id,
            'status_id' => $statusCanceled->id,
            'user_id' => $event->author->id
        ]);
    }
}
